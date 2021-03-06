<?php

namespace tppro;

$directory = "files/";
$partition_id = 0; 

error_reporting ( E_ALL );

require_once __DIR__ . '/lib/Thrift/ClassLoader/ThriftClassLoader.php';

use Thrift\ClassLoader\ThriftClassLoader;

$GEN_DIR = realpath ( dirname ( __FILE__ ) ) . '/gen-php';

$loader = new ThriftClassLoader ();
$loader->registerNamespace ( 'Thrift', __DIR__ . '/lib' );
$loader->registerDefinition ( 'shared', $GEN_DIR );
$loader->registerDefinition ( 'tppro', $GEN_DIR );
$loader->register ();

/*
 * Licensed to the Apache Software Foundation (ASF) under one or more
 * contributor license agreements. See the NOTICE file distributed with this
 * work for additional information regarding copyright ownership. The ASF
 * licenses this file to you under the Apache License, Version 2.0 (the
 * "License"); you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 * http://www.apache.org/licenses/LICENSE-2.0 Unless required by applicable law
 * or agreed to in writing, software distributed under the License is
 * distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY
 * KIND, either express or implied. See the License for the specific language
 * governing permissions and limitations under the License.
 */

use Thrift\Protocol\TBinaryProtocol;
use Thrift\Transport\TSocket;
use Thrift\Transport\THttpClient;
use Thrift\Transport\TBufferedTransport;
use Thrift\Exception\TException;

try {
	
	$socket = new TSocket ( 'localhost', 9090 );
	$transport = new TBufferedTransport ( $socket, 1024, 1024 );
	$protocol = new TBinaryProtocol ( $transport );
	$client = new IndexNodeClient($protocol);
	
	$transport->open ();
	
	if(!$client->containsPartition($partition_id)){
		$client->createPartition($partition_id);
	}
	
    $files = glob($directory . "*.txt");
    $documents = array();
	
	foreach($files as $file){
		echo "Se procesa: $file<br>";
		$documents[]=parse($file);
  	}

    foreach ($documents as $document) {
      $client->index($partition_id, array($document));
    }
    
	$results = $client->search(0, "text:world", 10, 0);
	
	foreach($results->hits as $hit){
		print "<p>".$hit->doc->fields["text"]."</p>";
	}
	

	$transport->close();
} catch ( TException $tx ) {
	print 'TException: ' . $tx->getMessage () . "\n";
}

function parse($file=null){
  $doc = new Document();
  
  if (file_exists($file)) {
    $fp = fopen($file, "r");
    $content = fread($fp, filesize($file));
    fclose($fp);
  }
  
  //echo "$content<br>";
  
  $doc->fields = json_decode($content, true);
  
  //var_dump($doc);
  
  return $doc;
}

?>