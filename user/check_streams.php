<?php
include_once("../auth.php");
include_once("auth_class.php");

function get_game_ids()
{
    include_once(__DIR__ . "/../../config.php");
    $username = $_SESSION["username"];
    $sql = "SELECT game_id FROM check_streams WHERE user = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    if (!empty($result)) {
        while ($row = mysqli_fetch_array($result)) {
            $game_ids_arr[] = $row["game_id"];
        }
        return $game_ids_arr;
    } else {
        return NULL;
    }
}

function construct_url($game_ids)
{
    $url = "";
    for ($i = 0; $i < sizeof($game_ids); $i++) {
        if ($i === 0) {
            $url .= "?game_id=" . $game_ids[$i];
        } else {
            $url .= "&game_id=" . $game_ids[$i];
        }
    }
    return $url;
}

$obj = new HandleAuth();
$test_token = $obj->execute_token_check();

$ini = parse_ini_file(__DIR__ . '/../../cfg.ini', true);

$url_no_params = "https://api.twitch.tv/helix/streams";

$all_data = "";

$game_ids = get_game_ids();
if ($game_ids !== NULL) {
    $headers = array();
    $headers[] = 'Client-Id:' . $ini["DEFAULT"]['client_id'];
    $headers[] = 'Authorization:' . $ini["DEFAULT"]['authorization'];

    $whole_url = $url_no_params . construct_url($game_ids);

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $whole_url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $rest_req = curl_exec($ch);

    if ($rest_req === false) {
        print_r('Curl error: ' . curl_error($ch));
    }

    $json = json_decode($rest_req, true);

    $all_data = $json['data'];
} else {
    $all_data = NULL;
}
?>
<!DOCTYPE html>
<html lang=en>

<head>
    <title>Check livestreams | OnlyMans</title>
    <meta http-equiv="Content-type" content="text/html; charset=utf-8">
    <meta name="description" content="Check which livestreams are playing specific games">
    <meta name="viewport" content="width=device-width, height=device-height, viewport-fit=cover, initial-scale=1">
    <link rel="icon" href="public/favicon.svg">
    <link rel="stylesheet" href="../css/checkstreams_styles.css" type="text/css">
    <link rel="alternate" type="application/rss+xml" title="OnlyMans site news" href="/rss.xml">
    <link rel="sitemap" type="application/xml" title="Sitemap" href="/sitemap.xml">
        <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha384-1H217gwSVyLSIfaLxHbE7dRb3v4mYCKbpQvzx0cegeju1MVsGrX5xXxAvs/HgeFs" crossorigin="anonymous"></script>
    <script nonce="NGINX_CSP_NONCE">
	$(document).ready(function() {
		$(document).on("click", "table thead tr th:not(.no-sort)", function() {
			var table = $(this).parents("table");
			var rows  = $(this).parents("table").find("tbody tr").toArray().sort(TableComparer($(this).index()));
			var dir   = ($(this).hasClass("sort-asc")) ? "desc" : "asc";

			if (dir == "desc") {
				rows = rows.reverse();
			}

			for (var i = 0; i < rows.length; i++) {
				table.append(rows[i]);
			}

			table.find("thead tr th").removeClass("sort-asc").removeClass("sort-desc");
			$(this).removeClass("sort-asc").removeClass("sort-desc") .addClass("sort-" + dir);
		});

	});
	function TableComparer(index) {
		return function(a, b) {
			var val_a  = TableCellValue(a, index);
			var val_b  = TableCellValue(b, index);
			var result = ($.isNumeric(val_a) && $.isNumeric(val_b)) ? val_a - val_b : val_a.toString().localeCompare(val_b);

			return result;
		}
	}
	function TableCellValue(row, index) {
		return $(row).children("td").eq(index).text();
	}
	</script>
</head>

<body>
    <?php echo file_get_contents("../html/navigation.html"); ?>
    <div id="main-cont">
        <h1>Streams found</h1>
        <?php
        if ($all_data === NULL || count($all_data) === 0) {
            echo "<p>No streams found</p>";
        } else {
            echo "<table class='streams_table' border='2' align='center'>";
            echo "<thead>";
            echo "<tr>";
            echo "<th>Username</th>";
            echo "<th>Title</th>";
            echo "<th>Game name</th>";
            echo "<th>Language</th>";
            echo "<th>Viewer count</th>";
            echo "<th>Started at</th>";
            echo "<th>Command</th>";
            echo "</tr>";
            echo "</thead>";
            echo "<tbody>";
            foreach ($all_data as $data) {
                echo "<tr>";
                echo "<td><a href=\"https://www.twitch.tv/" . $data['user_name'] . "\" target=\"_blank\">" . $data['user_name'] . "</a></td>";
                echo "<td>" . $data['title'] . "</td>";
                echo "<td>" . $data['game_name'] . "</td>";
                echo "<td>" . $data['language'] . "</td>";
                echo "<td>" . $data['viewer_count'] . "</td>";
                echo "<td>" . $data['started_at'] . "</td>";
                echo "<td>" . "/raid " . $data['user_name'] . "</td>";
                echo "</tr>";
            }
            try {
                $cursor = $json['pagination']['cursor'];
                while ($cursor) {
                    $new_url = $whole_url . "&after=" . $cursor;
                    curl_setopt($ch, CURLOPT_URL, $new_url);
                    $pagination_req = curl_exec($ch);
                    $json2 = json_decode($pagination_req, true);
                    $all_data2 = $json2['data'];

                    foreach ($all_data2 as $data2) {
                        echo "<tr>";
                        echo "<td><a href=\"https://www.twitch.tv/" . $data2['user_name'] . "\" target=\"_blank\">" . $data2['user_name'] . "</a></td>";
                        echo "<td>" . $data2['title'] . "</td>";
                        echo "<td>" . $data2['game_name'] . "</td>";
                        echo "<td>" . $data2['language'] . "</td>";
                        echo "<td>" . $data2['viewer_count'] . "</td>";
                        echo "<td>" . $data2['started_at'] . "</td>";
                        echo "<td>" . "/raid " . $data2['user_name'] . "</td>";
                        echo "</tr>";
                    }
                    $cursor = $json2['pagination']['cursor'];
                }
                echo "</tbody>";
                echo "</table>";
            } catch (Exception $e) {
                echo 'No cursor: ', $e->getMessage(), "\n";
            } finally {
                curl_close($ch);
            }
        }
        echo file_get_contents("../html/footer.html");
        unset($ini);
        ?>
