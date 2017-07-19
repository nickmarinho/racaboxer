<?php
include_once APP_FOLDER . '/class/Dir.php';
/**
* class to create xml files
* @author Luciano Marinho - contato at lucianomarinho dot com dot br - 2011-11-10
*/
class Dir_Gallery extends Dir
{
	private static $instance;
	protected $_arraydata;
	public static function getInstance()
	{
		if(!self::$instance){ self::$instance = new self(); }
		return self::$instance;
	}
	public function setarraydata($arraydata){ $this->_arraydata = $arraydata; }
	public function getarraydata(){ return $this->_arraydata; }
	
	public function generateXml()
	{
		$array = $this->getdata();
		$this->setfile('config.xml');
		$this->setpath('gallery');
		
		if(!count($array) > 0)
		{
			$path = $this->getpath();
			$file = $this->getfile();
			if(is_file($path . '/' . $file)){ unlink($path . '/' . $file); }
		}
		else
		{
			$xml="<!-- don't edit this file because was generated automatically -->\n";
			$xml .= "<gallery>\n";
			$xml .= "	<items>\n";
	
			foreach($array as $k => $v)
			{
				$xml .= "		<item>\n";
				$xml .= "		 <thumb>" . THUMB_FOLDER . $v['foto'] . "</thumb>\n";
				$xml .= "		 <source>" . UPLOAD_FOLDER . $v['foto'] . "</source>\n";
				$xml .= "		 <description>Clique na foto e vote - " . utf8_encode($v['nome']) . "</description>\n";
				$xml .= "		 <link>http://" . $_SERVER['SERVER_NAME'] . "/fotos.php?id=" . $v['id'] . "</link>\n";
				$xml .= "		 <target>_self</target>\n";
				$xml .= "		</item>\n";
			}
	          
			$xml .= "	</items>\n";
			$xml .= "	<settings>\n";
			$xml .= "		<showReflections>1</showReflections>\n";
			$xml .= "		<slideShowDelay>2</slideShowDelay>\n";
			$xml .= "		<useScrollBar>1</useScrollBar>\n";
			$xml .= "		<imageAngle>30</imageAngle>\n";
			$xml .= "		<reflectionAlpha>30</reflectionAlpha>\n";
			$xml .= "		<caption>\n";
			$xml .= "			<frameAlpha>90</frameAlpha>\n";
			$xml .= "			<frameColor>16777215</frameColor>\n";
			$xml .= "			<textColor>16777215</textColor>\n";
			$xml .= "			<shadowAlpha>40</shadowAlpha>\n";
			$xml .= "			<multilingualFontSize>12</multilingualFontSize>\n";
			$xml .= "			<bgAlpha>30</bgAlpha>\n";
			$xml .= "		</caption>\n";
			$xml .= "		<startPosition>left</startPosition>\n";
			$xml .= "		<scrollbar>\n";
			$xml .= "			<handleAlpha>75</handleAlpha>\n";
			$xml .= "			<bgAlpha>40</bgAlpha>\n";
			$xml .= "			<dropShadow>20</dropShadow>\n";
			$xml .= "			<handleColor>16777215</handleColor>\n";
			$xml .= "			<innerShadow>20</innerShadow>\n";
			$xml .= "			<arrowsColor>16777215</arrowsColor>\n";
			$xml .= "			<arrowsAlpha>75</arrowsAlpha>\n";
			$xml .= "		</scrollbar>\n";
			$xml .= "		<background>\n";
			$xml .= "			<bgColor>16777215</bgColor>\n";
			$xml .= "			<transparentBG>1</transparentBG>\n";
			$xml .= "		</background>\n";
			$xml .= "		<flipDuration>1</flipDuration>\n";
			$xml .= "		<useHighlight>1</useHighlight>\n";
			$xml .= "		<preloader>\n";
			$xml .= "			<stripesAlpha>75</stripesAlpha>\n";
			$xml .= "			<dropShadow>30</dropShadow>\n";
			$xml .= "			<innerShadow>20</innerShadow>\n";
			$xml .= "			<barAlpha>50</barAlpha>\n";
			$xml .= "			<bgAlpha>60</bgAlpha>\n";
			$xml .= "		</preloader>\n";
			$xml .= "	</settings>\n";
			$xml .= "</gallery>\n";

	if(isset($_GET['debug']))
	{
		echo "<pre>";
		print_r($xml);
		echo "</pre>";
	}

			
			$this->setdata($xml);
			return $this->write();
		}
	}
}