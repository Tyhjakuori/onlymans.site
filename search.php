<?php
include_once(__DIR__ . "/../config.php");
$resp = null;
$resp_high = null;
$dateFrom = null;
$dateTo = null;
$dateFromChecked = null;
$dateToChecked = null;
$dateSearchChecked = null;
if (isset($_POST['uparameters'])) {
    $params = $_POST['uparameters'];
    $columns = $_POST['columns'];
    $dateSearch = $_POST['dateSearch'];
    $dateFrom = $_POST['dateFrom'];
    $dateTo = $_POST['dateTo'];
    $allowed = ["broadcaster", "creator_name", "game_id", "game_name", "title"];
    if (in_array($columns, $allowed)) {
        $column = $columns;
    } else {
        $column = "title";
    }
    if (isset($dateSearch)) {
        $dateSearchArr = explode("-", $dateSearch);
        if (count($dateSearchArr) === 3) {
            if (checkdate($dateSearchArr[1], $dateSearchArr[2], $dateSearchArr[0])) {
                $dateSearchChecked = $dateSearch;
            }
        }
    }
    if (isset($dateFrom)) {
        $dateFromArr = explode("-", $dateFrom);
        if (count($dateFromArr) === 3) {
            if (checkdate($dateFromArr[1], $dateFromArr[2], $dateFromArr[0])) {
                $dateFromChecked = "$dateFrom 00:00:00";
            }
        }
    }
    if (isset($dateTo)) {
        $dateToArr = explode("-", $dateTo);
        if (count($dateToArr) === 3) {
            if (checkdate($dateToArr[1], $dateToArr[2], $dateToArr[0])) {
                $dateToChecked = "$dateTo 23:59:59";
            }
        }
    }
    if (isset($dateFromChecked) && isset($dateToChecked) && isset($params)) {
        $sql = "SELECT * FROM clips WHERE $column LIKE ? AND created_at BETWEEN ? AND ?";
    } else if (isset($dateFromChecked) && isset($dateToChecked)) {
        $sql = "SELECT * FROM clips WHERE created_at BETWEEN ? AND ?";
    } else if (isset($dateSearchChecked)) {
        $sql = "SELECT * FROM clips WHERE date(created_at) = ?";
    } else {
        $sql = "SELECT * FROM clips WHERE $column LIKE ?";
    }
    $stmt = $conn->prepare($sql);
    $new_param = strtolower("%$params%");
    if (isset($dateFromChecked) && isset($dateToChecked) && isset($params)) {
        $stmt->bind_param("sss", $new_param, $dateFromChecked, $dateToChecked);
    } else if (isset($dateFromChecked) && isset($dateToChecked)) {
        $stmt->bind_param("ss", $dateFromChecked, $dateToChecked);
    } else if (isset($dateSearchChecked)) {
        $stmt->bind_param("s", $dateSearchChecked);
    } else {
        $stmt->bind_param("s", $new_param);
    }
    $stmt->execute();
    $resp = $stmt->get_result();
    $row_cnt = $resp->num_rows;
} else if (isset($_POST['highparams'])) {
    $high_params = $_POST['highparams'];
    $columns = $_POST['columns'];
    $allowed = ["title", "url", "user_name", "description", "game_name"];
    if (in_array($columns, $allowed)) {
        $column = $columns;
    } else {
        $column = "title";
    }
    $sql = "SELECT * FROM highlights WHERE $column LIKE ?";
    $stmt = $conn->prepare($sql);
    $new_param = strtolower("%$high_params%");
    $stmt->bind_param("s", $new_param);
    $stmt->execute();
    $resp_high = $stmt->get_result();
    $row_cnt = $resp_high->num_rows;
}
?>
<!DOCTYPE html>
<html lang=en>

<head>
    <title>Search | OnlyMans</title>
    <meta http-equiv="Content-type" content="text/html; charset=utf-8">
    <meta name="description" content="Search clips and highlights from OnlyMans users content">
    <meta name="viewport" content="width=device-width, height=device-height, viewport-fit=cover, initial-scale=1">
    <link rel="icon" href="public/favicon.svg">
    <link rel="stylesheet" href="css/search_styles.css" type="text/css">
    <link rel="alternate" type="application/rss+xml" title="OnlyMans site news" href="/rss.xml">
    <link rel="sitemap" type="application/xml" title="Sitemap" href="/sitemap.xml">
    <script nonce="NGINX_CSP_NONCE">
        document.addEventListener('click', function(e) {
            try {
                function findElementRecursive(element, tag) {
                    return element.nodeName === tag ? element :
                        findElementRecursive(element.parentNode, tag)
                }
                var descending_th_class = ' dir-d '
                var ascending_th_class = ' dir-u '
                var ascending_table_sort_class = 'asc'
                var regex_dir = / dir-(u|d) /
                var regex_table = /\bsortable\b/
                var alt_sort = e.shiftKey || e.altKey
                var element = findElementRecursive(e.target, 'TH')
                var tr = findElementRecursive(element, 'TR')
                var table = findElementRecursive(tr, 'TABLE')

                function reClassify(element, dir) {
                    element.className = element.className.replace(regex_dir, '') + dir
                }

                function getValue(element) {
                    return (
                        (alt_sort && element.getAttribute('data-sort-alt')) ||
                        element.getAttribute('data-sort') || element.innerText
                    )
                }
                if (regex_table.test(table.className)) {
                    var column_index
                    var nodes = tr.cells
                    for (var i = 0; i < nodes.length; i++) {
                        if (nodes[i] === element) {
                            column_index = element.getAttribute('data-sort-col') || i
                        } else {
                            reClassify(nodes[i], '')
                        }
                    }
                    var dir = descending_th_class
                    if (
                        element.className.indexOf(descending_th_class) !== -1 ||
                        (table.className.indexOf(ascending_table_sort_class) !== -1 &&
                            element.className.indexOf(ascending_th_class) == -1)
                    ) {
                        dir = ascending_th_class
                    }
                    reClassify(element, dir)
                    var org_tbody = table.tBodies[0]
                    var rows = [].slice.call(org_tbody.rows, 0)
                    var reverse = dir === ascending_th_class
                    rows.sort(function(a, b) {
                        var x = getValue((reverse ? a : b).cells[column_index])
                        var y = getValue((reverse ? b : a).cells[column_index])
                        return isNaN(x - y) ? x.localeCompare(y) : x - y
                    })
                    var clone_tbody = org_tbody.cloneNode()
                    while (rows.length) {
                        clone_tbody.appendChild(rows.splice(0, 1)[0])
                    }
                    table.replaceChild(clone_tbody, org_tbody)
                }
            } catch (error) {}
        });
    </script>
</head>

<body>
    <div class="main-cont">
        <?php echo file_get_contents("html/navigation.html"); ?>
        <h3 align='center'>Search results</h3>
        <h3><?php echo "Number of results: " . $row_cnt; ?></h3>
        <?php if ($resp_high !== null) : ?>
            <table class="highlight_table sortable" border='2' align='center'>
            <?php else : ?>
                <table class="clip_table sortable" border='2' align='center'>
                <?php endif; ?>
                <thead>
                    <tr>
                        <?php if ($resp_high !== null) : ?>
                            <th>Title</th>
                            <th>Broadcaster</th>
                            <th>Description</th>
                            <th>Game</th>
                            <th>Viewable</th>
                            <th>Date</th>
                            <th>Duration</th>
                            <th>Added to DB</th>
                        <?php else : ?>
                            <th>Thumbnail</th>
                            <th>Clip title</th>
                            <th>Broadcaster</th>
                            <th>Clipper</th>
                            <th>Game</th>
                            <th>View count</th>
                            <th>Duration</th>
                            <th>Date</th>
                            <th>Added to DB</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($resp_high) {
                        while ($fetch = mysqli_fetch_array($resp_high)) {
                            echo "<tr>";
                            echo "<td><a href=\"highlight.php?url={$fetch['url']}\">" . $fetch['title'] . "</a></td>";
                            echo "<td>" . $fetch['user_name'] . "</td>";
                            echo "<td>" . $fetch['description'] . "</td>";
                            echo "<td>" . $fetch['game_name'] . "</td>";
                            echo "<td>" . $fetch['viewable'] . "</td>";
                            echo "<td>" . $fetch['created_at'] . "</td>";
                            echo "<td>" . $fetch['duration'] . "</td>";
                            echo "<td>" . $fetch['added'] . "</td>";
                            echo "</tr>";
                        }
                    } else {
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
                        }
                    } ?>
                </tbody>
                </table>
                <?php echo file_get_contents("html/footer.html"); ?>
