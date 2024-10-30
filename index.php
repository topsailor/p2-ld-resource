<?php
// MySQL 연결 설정
$servername = "10.0.5.228";
$username = "hanflixdb";         // MySQL 사용자 이름
$password = "hanflixdbpw";  // MySQL 비밀번호
$dbname = "video_db";
$url_parent = "https://d1uvydkrkf695y.cloudfront.net";  // CDN 주소

// MySQL 연결 생성
$conn = new mysqli($servername, $username, $password, $dbname);

// 연결 확인
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// videolist 테이블에서 데이터 가져오기
$sql = "SELECT id, video_name, upload_time FROM video_files";
$result = $conn->query($sql);

// HTML 및 스타일 출력
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Video List</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            color: #333;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .container {
            width: 80%;
            max-width: 900px;
            background: #fff;
            box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.1);
            padding: 20px;
            border-radius: 10px;
        }
        h1 {
            text-align: center;
            color: #444;
        }
        ul {
            list-style-type: none;
            padding: 0;
        }
        li {
            background: #e0e0e0;
            margin: 10px 0;
            padding: 15px;
            border-radius: 5px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        li:hover {
            background-color: #d5d5d5;
        }
        a {
            text-decoration: none;
            color: #1a73e8;
        }
        a:hover {
            text-decoration: underline;
        }
        .button-group {
            display: flex;
            gap: 10px;
        }
        .action-btn {
            background-color: #1a73e8;
            color: white;
            padding: 8px 12px;
            border-radius: 5px;
            text-decoration: none;
        }
        .action-btn:hover {
            background-color: #0f5bbd;
        }
    </style>
</head>
<body>
    <div class="container">
        <?php
        if ($result->num_rows > 0) {
            // 테이블 데이터 출력
            echo "<h1>Video List</h1>";
            echo "<ul>";
            while ($row = $result->fetch_assoc()) {
                // 파일의 전체 URL 생성
                $full_url = $url_parent . '/' . $row["video_name"];

                echo "<li>";
                echo "<strong>" . $row["video_name"] . "</strong><br>";
                echo "<p class='url'>" . $row["upload_time"] . "</p>";

                // 다운로드 및 재생 버튼 추가
                echo "<div class='button-group'>";
                echo "<a class='action-btn' href='download.php?id=" . $row["id"] . "' download>Download</a>";
                echo "<a class='action-btn' href='play.php?id=" . $row["id"] . "' target='_blank'>Play</a>";
                echo "</div>";

                echo "</li>";
            }
            echo "</ul>";
        } else {
            echo "<h1>No Results Found</h1>";
        }

        // 연결 종료
        $conn->close();
        ?>
    </div>
</body>
</html>