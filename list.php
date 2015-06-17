
<?php
/*
ServerQ -- This Software is designed to gather information from
an active Half-Life Dedicated Server.  
This version is beta and is not designed for Production enviroment.

This file will allow you to display the defualt server, has all includes
required to run out of the box, just need to edit the config.inc.php file
located in the same dir as this file

Copyright (C) 2008  Jonathan Nedobity
    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/
require_once("classes/serverclass.php");
require_once("config.inc.php");
$server = $_GET['dispserv']; /*
Due to this following code snippet not being displayed correctly on
some systems.  Until this section is redone the defualt server will
show unless a 2nd server is specified.
if(!isset($_GET['dispserv'])){
	$i;
	do {
		echo '<div align="center">
	  <table width="286" border="0" cellspacing="0" cellpadding="0">
		<tr>
		  <td>Server</td>
		  <td><a href="';
		  echo $path;
		  echo "list.php?dispserv=defualt";
		  echo '">';
		  echo $server_array[0];
		  echo '</a>
		  </td>
		</tr>
	  </table>
	</div>';
	$i++;
	}
	while($i < $CONFIG['max_servers']);
}*/
if(!$server){
	$server = 'defualt';
}
$HL = new hldsrcon;
$HL->hldscn();
$HL->ServerInfo();
?>