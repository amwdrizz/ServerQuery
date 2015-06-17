<?php
/*
Loader File,

For ease of use you may include this file into your site and make
the necessary calls.  If the ?server= is left undefined it will
load the defualt server in the config file.

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
$server = $_GET['server']; // Get server name
$cfg = $_SERVER['DOCUMENT_ROOT']."/config.inc.php"; // Should work with most installs and should not have to alter
require_once("classes/ldr.php"); // DO NOT Change
$HL = new hldsrcon($cfg); // Initiates the class
// $CSS = new css($cfg); // not setup

$HL->maintpl(); // This is a defualt template  Our Mini Template has not been setup yet.

// IF plans to impliment into an existing site comment out above and include this file at the top of your site
/*
<?php
require_once("path/to/loader.php");
?>
*/

// Then where you want the info shown use

/*
<?php
$HL->maintpl();
?>
*/



?> 
