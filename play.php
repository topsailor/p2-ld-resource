<?php
// URL에서 파일 ID를 가져옵니다.
if (isset($_GET['id'])) {
    $fileId = $_GET['id'];

    // MySQL 연결 설정
    $servername = "10.0.5.228";
    $username = "hanflixdb";         // MySQL 사용자 이름
    $password = "hanflixdbpw";  // MySQL 비밀번호
    $dbname = "video_db";
    $url_parent = "https://d1uvydkrkf695y.cloudfront.net";

    // MySQL 연결 생성
    $conn = new mysqli($servername, $username, $password, $dbname);

    // 연결 확인
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // videolist 테이블에서 해당 ID의 비디오 정보 가져오기
    $sql = "SELECT video_name FROM video_files WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $fileId);
    $stmt->execute();
    $stmt->bind_result($video_name);
    $stmt->fetch();
    $stmt->close();
    $conn->close();

    if ($video_name) {
        // 비디오의 전체 URL 생성
        $fileUrl = $url_parent . '/' . $video_name;

        // 비디오 스트리밍 페이지 출력
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title><?php echo htmlspecialchars($video_name); ?></title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    background-color: #f4f4f9;
                    color: #333;
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    min-height: 100vh;
                    margin: 0;
                }
                .video-container {
                    width: 80%;
                    max-width: 900px;
                    background: #fff;
                    box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.1);
                    padding: 20px;
                    border-radius: 10px;
                }
                video {
                    width: 100%;
                    height: auto;
                    border-radius: 10px;
                }
                h1 {
                    text-align: center;
                    margin-bottom: 20px;
                }
            </style>
        </head>
        <body>
            <div class="video-container">
                <h1><?php echo htmlspecialchars($video_name); ?></h1>
                <video controls>
                    <source src="<?php echo htmlspecialchars($fileUrl); ?>" type="video/mp4">
                    Your browser does not support the video tag.
                </video>
            </div>
        </body>
        </html>
        <?php
    } else {
        echo "Video not found.";
    }
} else {
    echo "Invalid request.";
}
?>