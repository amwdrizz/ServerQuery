
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
*/
require_once("sources/gameserver/classes/serverclass.php");
$server = $_GET['server'];
$HL = new hldsrcon;
// $HL->serverip($server);
//echo $SIP;
//echo "<br>";
//echo $HL->ipver();
// $HL->hldscn();
//$HL->ServerInfo();
 $HL->maintpl();
?>