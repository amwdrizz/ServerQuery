<?php
/*
Config File for ServerQ 
This Software is designed to gather information from
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

$server_array = array(
/* Usage, On the left of => in the ' ' on each new line keeping
the same format enter your server name you wish to use.  On the
right of => in the ' ' replace with your IP address or LDA (Linked 
Domain Name Address) to your server.

YOU MUST LEAVE DEFUALT, you can change the IP/LDA to your server.

*/
'defualt' 				=> '10.0.0.2', // DO NOT DELETE DEFUALT, You may change to your IP
'p3x-m249' 				=> '10.0.0.4', // Safe to delete. if only using 1 server

);
$path = '/';
$tpl = array(
	'path' => 'templates/',
	'large' => 'main.html',
	'mini' => 'mini.html',
	);
$local_array = array(
/*
If you use your server on an internal network that is broadcasted
to the internet, and you would like to query the server from the same
network specify the server ip below
*/
'defualt' => '10.0.0.4',
'p3x-m249' => '10.0.0.4',
);

$CONFIG = array(
"port"					=> '27015', // Change to your server NOTE THIS IS GLOBAL
"max_servers" 			=> '5', // Max Servers to List
"globalport" 			=> 'Y', // Replace with N to deactivate note this require :port in the above array as well!,  Y to enable
"rcon_pwd"				=> '', // Your server rcon password goes here,  If using more than one server, you must use the same pwd
);

?>