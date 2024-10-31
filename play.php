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

    // 디버깅: 비디오 이름이 제대로 가져왔는지 확인
    // if ($video_name) {
    //     echo "Video Name: " . $video_name . "<br>";
    // } else {
    //     die("Error: Video not found in the database.");
    // }

    // 비디오 URL 생성
    if ($video_name) {
        $fileUrl = $url_parent . '/' . $video_name;

    } else {
        die("Error: Video not found.");
    }

    // 비디오 스트리밍 페이지 출력
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!-- <title><?php echo htmlspecialchars($video_name); ?></title> -->
        <title>Hanflix Web: Play</title>
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
            <h1>Now Playing</h1>
             <!-- <h1><?php echo htmlspecialchars($video_name); ?></h1> -->
            <video id="video" controls></video>
        </div>

        <!-- hls.js 라이브러리 추가 -->
        <script src="https://cdn.jsdelivr.net/npm/hls.js@latest"></script>
        <script>
            var video = document.getElementById('video');
            var videoSrc = '<?php echo htmlspecialchars($fileUrl); ?>';

            if (Hls.isSupported()) {
                var hls = new Hls();
                hls.loadSource(videoSrc);
                hls.attachMedia(video);
                hls.on(Hls.Events.MANIFEST_PARSED, function() {
                    video.play();
                });
            } else if (video.canPlayType('application/vnd.apple.mpegurl')) {
                // HLS를 기본적으로 지원하는 브라우저 (주로 Safari)
                video.src = videoSrc;
                video.addEventListener('loadedmetadata', function() {
                    video.play();
                });
            } else {
                alert('HLS is not supported on this browser.');
            }
        </script>
    </body>
    </html>
    <?php
} else {
    echo "Invalid request.";
}
?>