<?php
/*
	*********************************************************************
	* -> www.phplogcon.org <-											*
	* -----------------------------------------------------------------	*
	* StreamConfig has the capability to create a specific LogStream	*
	* object depending on a configured LogStream*Config object.			*
	*																	*
	* All directives are explained within this file						*
	*
	* Copyright (C) 2008 Adiscon GmbH.
	*
	* This file is part of phpLogCon.
	*
	* PhpLogCon is free software: you can redistribute it and/or modify
	* it under the terms of the GNU General Public License as published by
	* the Free Software Foundation, either version 3 of the License, or
	* (at your option) any later version.
	*
	* PhpLogCon is distributed in the hope that it will be useful,
	* but WITHOUT ANY WARRANTY; without even the implied warranty of
	* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	* GNU General Public License for more details.
	*
	* You should have received a copy of the GNU General Public License
	* along with phpLogCon. If not, see <http://www.gnu.org/licenses/>.
	*
	* A copy of the GPL can be found in the file "COPYING" in this
	* distribution.
	*********************************************************************
*/

// --- Avoid directly accessing this file! 
if ( !defined('IN_PHPLOGCON') )
{
	die('Hacking attempt');
	exit;
}
// --- 

class LogStreamConfigDisk extends LogStreamConfig {
	public $FileName = '';
	public $LineParserType = "syslog"; // Default = Syslog!
	public $_lineParser = null;

	public function LogStreamFactory($o) 
	{
		// An instance is created, then include the logstreamdisk class as well!
		global $gl_root_path;
		require_once($gl_root_path . 'classes/logstreamdisk.class.php');

		// Create and set LineParser Instance
		$this->_lineParser = $this->CreateLineParser();
		
		// return LogStreamDisk instance
		return new LogStreamDisk($o);
	}

	private function CreateLineParser() 
	{
		// We need to include Line Parser on demand!
		global $gl_root_path;
		require_once($gl_root_path . 'classes/logstreamlineparser.class.php');
		
		// Probe if file exists then include it!
		$strIncludeFile = $gl_root_path . 'classes/logstreamlineparser' . $this->LineParserType . '.class.php';
		$strClassName = "LogStreamLineParser" . $this->LineParserType;

		if ( is_file($strIncludeFile) )
		{
			require_once($strIncludeFile);

			// TODO! Create Parser based on Source Config!

			//return LineParser Instance
			return new $strClassName();
		}
		else
			DieWithErrorMsg("Couldn't locate LineParser include file '" . $strIncludeFile . "'");
	}

}
?>
