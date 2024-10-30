<?php
// 파일 다운로드를 처리하기 위한 스크립트

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

    // videolist 테이블에서 특정 ID에 해당하는 비디오 정보 가져오기
    $sql = "SELECT video_name FROM video_files WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $fileId);
    $stmt->execute();
    $stmt->bind_result($video_name);
    $stmt->fetch();
    $stmt->close();
    $conn->close();

    if ($video_name) {
        // 파일의 전체 URL 생성
        $fileUrl = $url_parent . '/' . $video_name;

        // 파일 다운로드를 처리하는 헤더 설정
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . basename($fileUrl) . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($fileUrl));

        // 파일 읽기 및 출력
        readfile($fileUrl);
        exit;
    } else {
        echo "File not found.";
    }
} else {
    echo "Invalid request.";
}
?>