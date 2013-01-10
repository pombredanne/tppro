package ar.uba.fi.tppro.core.index;

import java.io.File;
import java.io.IOException;
import java.util.List;
import java.util.Map;

import org.apache.lucene.analysis.standard.StandardAnalyzer;
import org.apache.lucene.document.Field;
import org.apache.lucene.document.TextField;
import org.apache.lucene.index.DirectoryReader;
import org.apache.lucene.index.IndexReader;
import org.apache.lucene.index.IndexWriter;
import org.apache.lucene.index.IndexWriterConfig;
import org.apache.lucene.index.IndexWriterConfig.OpenMode;
import org.apache.lucene.index.IndexableField;
import org.apache.lucene.queryparser.classic.QueryParser;
import org.apache.lucene.search.IndexSearcher;
import org.apache.lucene.search.Query;
import org.apache.lucene.search.ScoreDoc;
import org.apache.lucene.search.SearcherFactory;
import org.apache.lucene.search.SearcherManager;
import org.apache.lucene.search.TopScoreDocCollector;
import org.apache.lucene.store.Directory;
import org.apache.lucene.store.FSDirectory;
import org.apache.lucene.util.Version;
import org.apache.thrift.TException;
import org.slf4j.Logger;
import org.slf4j.LoggerFactory;

import com.google.common.collect.Lists;
import com.google.common.collect.Maps;

import ar.uba.fi.tppro.core.service.IndexCoreHandler;
import ar.uba.fi.tppro.core.service.thrift.Document;
import ar.uba.fi.tppro.core.service.thrift.Hit;
import ar.uba.fi.tppro.core.service.thrift.ParseException;
import ar.uba.fi.tppro.core.service.thrift.QueryResult;

public class IndexPartition {

	final Logger logger = LoggerFactory.getLogger(IndexPartition.class);

	private static final String DEFAULT_FIELD = "text";

	private String defaultField = DEFAULT_FIELD;
	private IndexWriterConfig config;
	private Directory indexDir;
	private StandardAnalyzer analyzer;

	private SearcherManager mgr;

	public IndexPartition(File path) throws IOException {
		analyzer = new StandardAnalyzer(Version.LUCENE_40);
		indexDir = FSDirectory.open(path);
		config = new IndexWriterConfig(Version.LUCENE_40, analyzer);
		config.setOpenMode(OpenMode.CREATE_OR_APPEND);
		
		if(path.list().length == 0){
			IndexWriter initialWriter = new IndexWriter(indexDir, config);
			initialWriter.close();
		}
		
		mgr = new SearcherManager(indexDir, new SearcherFactory());
	}

	public void index(List<Document> documents) throws IOException {

		long startTime = System.currentTimeMillis();

		IndexWriter w;
		w = new IndexWriter(indexDir, config);

		for (Document document : documents) {
			org.apache.lucene.document.Document luceneDoc = new org.apache.lucene.document.Document();

			for (Map.Entry<String, String> field : document.fields.entrySet()) {
				luceneDoc.add(new TextField(field.getKey(), field.getValue(),
						Field.Store.YES));
			}
			w.addDocument(luceneDoc);
		}

		w.commit();
		w.close();
		
		mgr.maybeRefresh();
		
		long endTime = System.currentTimeMillis();
		
		logger.debug(String.format("INDEXED: DocCount=%d IndexTime=%d", documents.size(), endTime - startTime));

	}

	public QueryResult search(int partitionId, String query, int limit,
			int offset) throws ParseException, IOException {
		
		long startTime = System.currentTimeMillis();

		Query q;
		try {
			q = new QueryParser(Version.LUCENE_40, defaultField, analyzer)
					.parse(query);
		} catch (org.apache.lucene.queryparser.classic.ParseException e) {
			throw new ParseException(e.getMessage());
		}

		IndexSearcher searcher = mgr.acquire();
		QueryResult queryResult = new QueryResult();

		try {

			TopScoreDocCollector collector = TopScoreDocCollector.create(limit,
					true);

			searcher.search(q, collector);

			ScoreDoc[] hits = collector.topDocs(offset).scoreDocs;

			queryResult.hits = Lists.newArrayList();
			queryResult.parsedQuery = q.toString();
			queryResult.totalHits = collector.getTotalHits();

			for (int i = 0; i < hits.length; i++) {
				int docId = hits[i].doc;
				float score = hits[i].score;

				org.apache.lucene.document.Document d = searcher.doc(docId);

				Hit hit = new Hit();
				hit.doc = new Document();
				hit.doc.fields = Maps.newHashMap();
				hit.score = score;

				for (IndexableField field : d.getFields()) {
					if (field.fieldType().stored()) {
						String name = field.name();
						String val = field.stringValue();

						hit.doc.fields.put(name, val);
					}
				}

				queryResult.hits.add(hit);
			}

		} finally {
			mgr.release(searcher);
		}
		
		long endTime = System.currentTimeMillis();
		logger.debug(String.format("QUERY: Partition=%d query=[%s] limit=%d offset=%d -> qtime=%d hits=%d", partitionId, query, limit, offset, endTime - startTime, queryResult.totalHits));

		return queryResult;

	}

	public String getDefaultField() {
		return defaultField;
	}

	public void setDefaultField(String defaultField) {
		this.defaultField = defaultField;
	}
}