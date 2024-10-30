<?php
// MySQL 연결 설정
$servername = "localhost";
$username = "root";         // MySQL 사용자 이름
$password = "hanflixdbpw"; // MySQL 비밀번호
$dbname = "test1";

// MySQL 연결 생성
$conn = new mysqli($servername, $username, $password, $dbname);

// 연결 확인
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// videolist 테이블에서 데이터 가져오기
$sql = "SELECT id, name, url FROM testlist";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // 테이블 데이터 출력
    echo "<h1>Video List</h1>";
    echo "<ul>";
    while ($row = $result->fetch_assoc()) {
        echo "<li>";
        echo "<strong>" . $row["title"] . "</strong><br>";
        // 다운로드 링크를 download.php로 연결
        echo "<a href='download.php?id=" . $row["id"] . "'>Download " . $row["title"] . "</a><br>";
        echo "<p>" . $row["url"] . "</p>";
        echo "</li>";
    }
    echo "</ul>";
} else {
    echo "0 results";
}

// 연결 종료
$conn->close();
?>