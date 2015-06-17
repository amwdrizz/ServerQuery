<?php
/*
ServerQ -- This Software is designed to gather information from
an active Half-Life Dedicated Server.  
This version is beta and is not designed for Production enviroment.

Copyright (C) 2008  Jonathan Nedobity
    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.

To call serverq with out templates, only after you got the templates working
use 
$server = $_GET['server'];
$HL = new hldsrcon; // This will activate the class
$HL->ServerInfo(); // This will get ServerInfo, YOU WILL NEED TO FORMAT ON YOUR
OWN WITH THIS METHOD!
$HL->maintpl(); // Comment out $HL->ServerInfo() to use this method
*/
class hldsrcon
{
	var $port;
	var $curserver;
	var $ip;
	var $maxservers;
	var $globalport;
	function __construct($cfg) 
	/* 
	This will assign server ip/dns with TRUE server name (EG COMPUTER NAME)
	It will also load all necessary CFG items from the correct config file
	and place them into use for this class
	*/
	{
		/************************************
		Lets Load all config File info
		Lets also set all important vars to
		connect to the HLDS Server
		************************************/
		$this->cfgpath = $cfg;
		$doc_root = $_SERVER['DOCUMENT_ROOT'];
		require_once($this->cfgpath);
		$this->globalport = $CONFIG['globalport'];
		$this->maxservers = $CONFIG['max_servers'];
		$this->port = $CONFIG['port'];
		$this->server_password = $CONFIG['rcon_pwd'];
		$this->tplpath = $tpl['path'];
		$this->tplmain = $tpl['large'];
		$this->tplmini = $tpl['mini'];
		$this->debug = false;
		$hlds='';
		// Assign Servername with IP/LDA
		global $server;
		if(!$server || !array_key_exists($sever, $server_array)){
			$server = 'defualt';
			$hlds = $server_array[ $server ];
		}
		else{
			$hlds = $server_array[ $server ];
		}
		/* Required for use if  the server is on internal network(LAN)
		And website is on external network (INTERNET) that is publicly
		viewable. */
		$this->uip = $_SERVER['REMOTE_ADDR']; // User IP
		if($this->curserver == $this->uip){ // Does Userip = ServerIp?
			// This next IF block is if the above is true
			/*<--- Begin --->*/
			if(!$server || !array_key_exists($server, $local_array)){
				$server = 'defualt';
				unset($hlds);
				$hlds = $local_array[ $server ];
			}
			else{
				unset($hlds);		
				$hlds = $local_array[ $server ];
			}
			// End
		}
		// If the userip=serverip if block failed then server ip = external ip
		$this->curserver=$hlds;
		return $this->curserver; // Returns ip value back to the class
	}
	
	function hldscn() // make connection to the server
	{
		$fp = fsockopen("udp://". $this->curserver,$this->port,$errno, $errorstr, 2);
		stream_set_timeout($fp, 2);
		if($fp){
			$this->connected = true;
		}
		else{
			$this->connected = false;
		}
		$this->socket = $fp;
		return $this->connected;
	}
	
	function Disconnect() // disconnect from server
	{
		//close socket
		fclose($this->socket);
		$connected = false;
	
	}
	/*
	This function will turn any hex form chars from any
	server output and gather the ASCII text code number
	usefull for debugging puposes
	*/
  	function ascii_decode($input)
	{
		echo "<br>";
		echo "The input is : ";
		echo $input;
		echo "<br>";
		$strn = strlen($input);
		echo "Number of Chars are : ";
		echo $strn;
		echo "<br>";
		echo "ASCII CODE STRING IS,  Please Refer to an ASCII Sheet for translation";
		echo "<br>";
		while($strn > $i){
			$i;
			echo ord($input[$i]);
			echo " ";
			$i++;
		}
	}
	function RconCommand($command, $pagenumber = 0, $single = true)
  	{
    //If there is no open connection return false
    if(!$this->connected)
      return $this->connected;

    //get challenge number
    if($this->challenge_number == "")
    {
      //send request of challenge number
      $challenge = "\xff\xff\xff\xff\x57";
      $buffer = $this->Communicate($challenge);
		$this->ascii_decode($challenge);
      //If no connection is open
      if(trim($buffer) == "")
      {
        $this->connected = false;
        return false;
      }
	//if($this->debug == true){
		$this->ascii_decode($buffer);
	//}
      //get challenge number
      $buffer = explode(" ", $buffer);
      $this->challenge_number = $buffer;
    }

    //build command
    // $command = "\xff\xff\xff\xffrcon $this->challenge_number \"$this->server_password\" $command\n";
	//$command = "\xff\xff\xff\xff $command $this->challenge_number";
	//$command = $command.$this->challenge_number;
	//echo $command;
    //get specified page
    $result = "";
    $buffer = "";
    while($pagenumber >= 0)
    {
      //send rcon command
      $buffer .= $this->Communicate($command);

      //get only one package
      if($single == true)
        $result = $buffer;

      //get more then one package and put them together
      else
        $result .= $buffer;

      //clear command for higher iterations
      $command = "";

      $pagenumber--;

    } //while($pagenumber >= 0)

    //return unformatted result
    return trim($result);

  }
    
	//Communication between ServerQuery and the Gameserver
  	function Communicate($command)
  	{
    //If there is no open connection return false
    if(!$this->connected)
      return $this->connected;


    //write command on socket
    if($command != "")
      fputs($this->socket, $command, strlen($command));

    //get results from server
    $buffer = fread ($this->socket, 1);
    $status = socket_get_status($this->socket);

    // echo $this->socket.": ".$status["unread_bytes"] . "<br>";
    // print_r($status);
    // Sander's fix:
    if ($status["unread_bytes"] > 0) {
    	$buffer .= fread($this->socket, $status["unread_bytes"]);
    }


    //If there is another package waiting
    if(substr($buffer, 0, 4) == "\xfe\xff\xff\xff")
    {
      //get results from server
      $buffer2 = fread ($this->socket, 1);
      $status = socket_get_status($this->socket);
      $buffer2 .= fread($this->socket, $status["unread_bytes"]);

      //If the second one came first
      if(strlen($buffer) > strlen($buffer2))
        $buffer = substr($buffer, 14) . substr($buffer2, 9);
      else
        $buffer = substr($buffer2, 14) . substr($buffer, 9);

    }

    //In case there is only one package
    else
      $buffer = substr($buffer, 5);


    //return unformatted result
    return $buffer;

  }
	function footer()
	{
		$this->vc = $_SERVER['DOCUMENT_ROOT']."/classes/vc.php";
		//echo $this->vc;
		require_once($this->vc);
		$this->version = $appinfo["version"];
		$this->pubnm = $appinfo["Pub_nm"];
		$this->creator = $appinfo["Creator"];
		$this->lb = $appinfo["linkbk"];
		$this->space = " ";
		$foot = $this->pubnm.$this->space.$this->version.$this->space."Created by ".$this->creator."<br> Link Back <a href ='".$this->lb."'>". $this->pubnm."</a>";
		// echo $foot;
		return $foot;
	}
    function ServerStatus()
	{
		if(!$this->connected == true){
			$this->status = "Server Offline";
		}
		else{
			$this->status = "Server Online";
		}
		return $this->status;
	}
	function ServerInfo()
	{
		$this->hldscn();

		//If there is no open connection return false
		if(!$this->connected){
			return $this->connected;
		}
		global $status;
		$status = $this->RconCommand("\xFF\xFF\xFF\xFFTSource Engine Query\x00");
		
		$players = $this->RconCommand("\xFF \xFF \xFF \xFF \x55 ".$this->challenge_number);
		
		if($this->debug == true){ // This handles Status Debug
			$this->ascii_decode($status);
		}
		
		if($this->debug == true){
			$this->ascii_decode($players);
		}
		$status = explode(chr(0),$status);
	
		$this->hostname = $status[1];
		$this->servip = $status[0];
		$this->map = $status[2];
		$this->mod = $status[3];
		$this->game = $status[4];
		$this->active = $result["activeplayers"] = $active[0];
		$this->maxplayers = $result["maxplayers"] = substr($active[2], 1);
		$sep = '/';
		$this->cpl = $this->active.$sep.$this->maxplayers;
		$parse_array = array(
				'hostname' => $this->hostname ,
				'servip' => $this->servip ,
				'curmap' => $this->map ,
				'mod' => $this->mod ,
				'game' => $this->game ,
				'players' => $this->cpl ,
				'status' => $this->ServerStatus(),
				'footer' => $this->footer(),
				);
	return $parse_array;

  } //function ServerInfo()

	function maintpl()
	{
		$this->curtpl = $this->tplpath.$this->tplmain;
		$parse_array = $this->ServerInfo();
		$data = "<!-- Generated by ". $this->pubnm ." Version ". $this->version ." by ". $this->creator ." -->";
		$data .= $this->parse( $parse_array, $this->curtpl );
		echo $data;
	}
	function minitpl()
	{
		$this->curtpl = $this->tplpath.$this->tplmini;
		$parse_array = $this->ServerInfo();
		$data = "<!-- Generated by ServerQuery Version .1b by Jonathan Nedobity -->";
		$data .= $this->parse( $parse_array, $this->curtpl );
		echo $data;
	}

	
	function parse( $parse_array , $template ) {
		$fp = fopen($template, 'r');
		$html = fread($fp, filesize($template));
		fclose($fp);
		clearstatcache();
		foreach ($parse_array as $key => $value) {
			$key = "###" . $key . "###";
			$html = str_replace( $key , $value , $html );
		}
		return $html;

	}

	

}
?>