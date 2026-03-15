<?php
include_once(__DIR__ . "/../config.php");
$resp = null;
$resp_high = null;
if (isset($_POST['uparameters'])) {
    $params = $_POST['uparameters'];
    $columns = $_POST['columns'];
    $allowed = ["broadcaster", "creator_name", "game_id", "game_name", "title"];
    if (in_array($columns, $allowed)) {
        $column = $columns;
    } else {
        $column = "title";
    }
    $sql = "SELECT * FROM clips WHERE $column LIKE ?";
    $stmt = $conn->prepare($sql);
    $new_param = strtolower("%$params%");
    $stmt->bind_param("s", $new_param);
    $stmt->execute();
    $resp = $stmt->get_result();
    $row_cnt = $resp->num_rows;
}
?>
<!DOCTYPE html>
<html lang=en>

<head>
    <title>Search | Essie OnlyMans</title>
    <meta http-equiv="Content-type" content="text/html; charset=utf-8">
    <meta name="description" content="Search Essie clips">
    <meta name="viewport" content="width=device-width, height=device-height, viewport-fit=cover, initial-scale=1">
    <link rel="icon" href="public/favicon.svg">
    <link rel="stylesheet" href="css/search_styles.css" type="text/css">
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
	<div class="main-cont">
        <?php echo file_get_contents("html/navigation.html"); ?>
		<h3 align='center'>Search results</h3>
		<h3><?php echo "Number of results: " . $row_cnt; ?></h3>
            <table class="clip_table" border='2' align='center'>
                <thead>
                    <tr>
                            <th>Thumbnail</th>
                            <th>Clip title</th>
                            <th>Broadcaster</th>
                            <th>Clipper</th>
                            <th>Game</th>
                            <th>View count</th>
                            <th>Duration</th>
                            <th>Date</th>
                            <th>Added to DB</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        while ($fetch = mysqli_fetch_array($resp)) {
                            echo "<tr>";
                            if ($fetch['thumbnail_url'] === NULL) {
                                echo "<td></td>";
                            } else {
                                echo "<td><img src={$fetch['thumbnail_url']} height='80px' width='180px' rel='preload'></td>";
                            }
                            echo "<td><a href='clip.php?clipid={$fetch['name']}'>" . $fetch['title'] . "</a></td>";
                            echo "<td>" . $fetch['broadcaster'] . "</td>";
                            echo "<td>" . $fetch['creator_name'] . "</td>";
                            if ($fetch['game_name'] === NULL) {
                                echo "<td>" . $fetch['game_id'] . "</td>";
                            } else {
                                echo "<td>" . $fetch['game_name'] . "</td>";
                            }
                            echo "<td>" . $fetch['view_count'] . "</td>";
                            echo "<td>" . $fetch['duration'] . "</td>";
                            echo "<td>" . $fetch['created_at'] . "</td>";
                            echo "<td>" . $fetch['added'] . "</td>";
                            echo "</tr>";
                        }?>
                </tbody>
                </table>
<?php echo file_get_contents("html/footer.html"); ?>

