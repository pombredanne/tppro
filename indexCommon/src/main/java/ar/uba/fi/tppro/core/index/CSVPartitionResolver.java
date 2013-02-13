package ar.uba.fi.tppro.core.index;

import java.io.BufferedReader;
import java.io.File;
import java.io.FileNotFoundException;
import java.io.FileReader;
import java.io.IOException;
import java.util.ArrayList;
import java.util.Collection;
import java.util.List;

import ar.uba.fi.tppro.core.index.IndexNodeDescriptor;

import com.google.common.collect.LinkedListMultimap;
import com.google.common.collect.Multimap;

public class CSVPartitionResolver extends AbstractPartitionResolver {
	
	public CSVPartitionResolver(RemoteNodePool nodePool) {
		super(nodePool);
	}

	/**
	 * host,port,partitionId
	 * 
	 * @param file
	 * @throws IOException
	 */
	
	public void load(File file) throws IOException{
		
		BufferedReader reader = new BufferedReader(new FileReader(file));
		
		String line = reader.readLine();
		while(line != null){
			String[] parts = line.split(",");
			
			String host = parts[0];
			String port = parts[1];
			String pId = parts[2];
			
			addSocketDescription(host, Integer.parseInt(port), Integer.parseInt(pId));
			
			line = reader.readLine();
		}
	}

	
}
