# Casper CTF Template


------------

### 1. Abstract

이 템플릿은 [CASPER](https://casper.or.kr) 에서 사용할 CTF 템플릿 입니다. 

개발 목적은 CASPER 만의 독자적인 CTF 템플릿이 있으면 좋겠다는 생각으로 개발을 시작했습니다.

------------

### 2. Environment

이 템블릿을 사용하기 위해서는 아래와 같은 환경이 구성되어 있어야 합니다.

- Server: Apache2 (Or anything)
- Server Side: php 7.2.24
- DB: Mysql

------------

### 3. How to install

위 파일을 모두 다운 받은 뒤, 해야할 것들이 2가지가 있습니다.

#### - Modify "db.php"

db.php는 DB에 접근하기 위한 파일입니다.

해당 코드를 보면 user 와 pw가 있습니다.

```php
<?php
    $host = 'localhost';
    $user = 'userid';
    $pw = 'userpw';
    $dbname = 'CCTF';
    $mysqli = mysqli_connect($host, $user, $pw, $dbname);
    
    if(!$mysqli){
        echo "<script>alert('DB connection is fail.');</script>";
    }
?>
```

3번째와 4번째 값을 사용자의 정보에 맞게 수정하면 됩니다.

#### - Insert DB file "CCTF-Template.sql"

위 파일 중에 CCTF-Template.sql 이라는 SQL 파일이 존재합니다.

이를 Mysql에 넣으면 설치가 끝납니다.

설치된 DB에는 user 테이블이 있는데, admin의 계정이 존재합니다.

초기 비밀번호는 아래와 같습니다.

```
userid: admin
password: admin1234
```

------------

#### 4. Details

사용한 라이브러리 목록은 아래와 같습니다.

- Bootstrap.js
- Chart.js
- datepicker.js
- Fontawesome
- Jquery



다음은 디렉토리 및 파일에 대한 세부 설명과 DB의 구조 및 세부 설명을 적은 것 입니다.

#### - Directories and Files

현재 디렉토리의 구조는 아래와 같습니다.

```
├── admin        # 어드민 페이지와 관련된 디렉토리 입니다. CTF 설명과 관련된 작업을 수행하는 곳 입니다.
├── challenge    # 문제와 관련된 업로드 파일들을 저장하는 곳 입니다.
├── css
│   ├── admin             # 어드민 페이지에서만 동작하는 CSS 파일 입니다.
│   │   └── datepicker    # 달력을 구현하기 위한 datepicker.js 라이브러리의 CSS 파일 입니다.
│   └── bootstrap         # bootstrap.js 라이브러리의 CSS 파일 입니다.
├── images                # 페이지를 꾸미기 위한 이미지 파일이 저장된 곳 입니다.
├── js
│   ├── admin             # 어드민 페이지에서만 동작하는 JS 파일들 입니다.
│   │   └── datepicker    # 달력을 구현하기 위한 datepicker.js 라이브러리의 JS 파일 입니다.
│   └── bootstrap         # bootstrap.js 라이브러리의 JS 파일 입니다.
└── uploads               # 기타 업로드 파일이 저장되는 곳 입니다.
    └── userImage         # 사용자의 프로필 사진이 저장되는 곳 입니다.
        └── ....          # 랜덤 디렉토리 이름이 생성되어 사용자의 프로필 사진이 저장 됩니다.
```


현재 파일의 구조는 아래와 같습니다.


```
├── CCTF-database.sql		# DB 파일 입니다.
├── README.md
├── admin
│   ├── config.php          # 어드민 페이지에서 요청한 것들을 처리하는 php 파일 입니다.
│   └── index.php           # 어드민 페이지를 보여주는 php 파일 입니다.
├── challenge
│   └── reverseMe.c
├── challenge.php           # challenge 리스트를 보여주는 php 파일 입니다.
├── checkFlag.php           # 사용자가 입력한 Flag 값을 검증 및 점수 부여 역할을 하는 파일 입니다.
├── config.php              # challenge.php 에서 "실시간 공지" 를 가져오기 위한 파일 입니다.
├── css
│   ├── admin
│   │   ├── admin-main.css   # 어드민 CSS 파일 입니다.
│   │   └── datepicker
│   │       ├── bootstrap-datetimepicker.css
│   │       └── bootstrap-datetimepicker.min.css
│   ├── bootstrap
│   │   ├── bootstrap-grid.css
│   │   └──...
│   ├── challenge-countdown.css    # challenge.php 에서 CTF가 끝나가는 시간을 표현하기 위한 CSS 파일 입니다.
│   ├── challenge.css              # challenge.php 의 CSS 파일 입니다.
│   ├── etc.css
│   ├── login.css                  # login.php 의 CSS 파일 입니다.
│   ├── main-countdown.css         # index.php 의 countdown 디자인과 관련된 CSS 파일 입니다.
│   ├── main.css                   # index.php 의 CSS 파일 입니다.
│   └── scoreboard.css             # scoreboard.php 의 CSS 파일 입니다.
├── db.php                         # db와 관련된 파일 입니다.
├── images
│   ├── 1.png
│   ├── 2.png
│   ├── 3.png
│   ├── Dev_RankBorders_min_ptv8l21r384e834nvzm5.png
│   ├── header_bg.jpg
│   ├── prob1.png
│   └── questionPerson.png
├── index.php                      # 메인 페이지 입니다.
├── js
│   ├── admin
│   │   ├── datepicker
│   │   │   ├── bootstrap-datetimepicker.js
│   │   │   └── ...
│   │   ├── etc.js
│   │   └── requestConfig.js       # admin/index.php 에서 CTF 설정을 바꾸면 admin/config.php 로 요청을 보내는 역할을 하는 JS 파일 입니다.
│   ├── bootstrap
│   │   ├── bootstrap.bundle.js
│   │   └── ...
│   ├── challenge-countdown.js     # challenge.php 에서 실시간 countdown을 구현하기 위한 JS 파일 입니다.
│   ├── challenge.js               # challenge.php 에서 동적인 페이지 역할을 하는 JS 파일 입니다.
│   ├── etc.js                     # 무시
│   ├── main-countdown.js          # index.php 에서 CTF 시작 시간 까지의 countdown을 구현하기 위한 JS 파일 입니다.
│   ├── requestFlag.js             # challenge.php 에서 사용자가 입력한 Flag 값을 checkFlag.php 로 request 하는 JS 파일 입니다.
│   └── scoreChart.js              # scoreboard.php 에서 사용자의 점수를 시간별로 그래프를 통해 구현하기 위한 JS 파일 입니다.
├── login.php                      # 로그인 및 회원가입 역할을 하는 파일 입니다.
├── scoreboard.php                 # 사용자의 점수(순위) 를 출력하는 파일 입니다.
└── uploads
    └── userImage
        ├── LQ53qk1k4L0UOZeH81YZam6NHXpZ6Luk
        │   └── cat.jpg
        └── ...
```

다음은 DB의 테이블 구조 및 설명 입니다.

```
+----------------+
| Tables_in_CCTF |
+----------------+
| announcement   |    # 공지가 저장되는 곳 입니다. ex) 사용자가 어떤 문제를 풀었다, 어드민의 공지 등등 
| category       |    # 문제의 카테고리가 저장되는 곳 입니다. ex) web, pwnable, reversing...
| challenge      |    # 문제가 저장되는 곳 입니다.
| config         |    # CTF의 설정이 저장되는 곳 입니다. ex) CTF 시작, 종료 시간, 로그인 및 회원 가입 가능 여부
| logs           |    # 사용자가 입력한 flag 값을 저장하는 곳 입니다. 어떤 유저가 어떤 문제에 어떤 flag 값을 입력했는지 알 수 있습니다.
| user           |    # 유저의 정보가 저장되는 곳 입니다.
+----------------+
```

다음은 테이블의 칼럼 구조 및 설명입니다.

```
mysql> desc announcement;
+----------+----------+------+-----+---------+----------------+
| Field    | Type     | Null | Key | Default | Extra          |
+----------+----------+------+-----+---------+----------------+
| idx      | int(11)  | NO   | PRI | NULL    | auto_increment |  
| category | text     | NO   |     | NULL    |                |  # 사용자의 액션인지, 관리자가 등록한 공지인지를 구분하기 위한 칼럼입니다.
| message  | text     | NO   |     | NULL    |                |  # 공지의 내용 입니다.
| date     | datetime | NO   |     | NULL    |                |  # 공지가 등록된 시간 입니다.
+----------+----------+------+-----+---------+----------------+


mysql> desc category;
+---------------+------+------+-----+---------+-------+
| Field         | Type | Null | Key | Default | Extra |
+---------------+------+------+-----+---------+-------+
| category_name | text | YES  |     | NULL    |       |   # 문제의 카테고리를 저장합니다.
+---------------+------+------+-----+---------+-------+


mysql> desc challenge;
+--------------+-------------+------+-----+---------+----------------+
| Field        | Type        | Null | Key | Default | Extra          |
+--------------+-------------+------+-----+---------+----------------+
| idx          | int(11)     | NO   | PRI | NULL    | auto_increment | 
| title        | text        | YES  |     | NULL    |                |  # 문제의 제목 입니다.
| contents     | text        | YES  |     | NULL    |                |  # 문제의 내용 입니다.
| flag         | text        | YES  |     | NULL    |                |  # 문제의 flag 값 입니다.
| points       | int(11)     | YES  |     | NULL    |                |  # 문제의 점수 입니다.
| bonus        | int(11)     | NO   |     | NULL    |                |  # 문제를 첫번째로 풀었을 때의 보너스 점수 입니다.
| decrease     | int(11)     | NO   |     | NULL    |                |  # 문제를 푼 사람 만큼 원래 점수에서 얼만큼 감소할 지 를 나타냅니다. 예를 들어 값이 10 이고, 푼 사람이 2명이면 (원래 점수) - (10) X (푼 사람 수) 의 값이 points 에 저장 됩니다.
| attach       | text        | YES  |     | NULL    |                |  # 첨부파일의 url이 저장됩니다.
| visible      | int(11)     | YES  |     | NULL    |                |  # 문제의 표시 여부를 설정합니다.(1 = visible)
| category     | text        | YES  |     | NULL    |                |  # 문제의 카테고리 입니다.
| solved       | int(11)     | NO   |     | NULL    |                |  # 몇명이 풀었는지 저장됩니다.
| first_solver | varchar(30) | NO   |     | NULL    |                |  # 첫번째로 푼 사용자의 nickname 이 저장됩니다.
| level        | varchar(20) | YES  |     | NULL    |                |  # 문제의 난이도를 나타냅니다.
| solver_list  | text        | NO   |     | NULL    |                |  # 문제를 푼 사용자의 nickname 이 저장됩니다.
+--------------+-------------+------+-----+---------+----------------+


mysql> desc config;
+--------------+----------+------+-----+---------+-------+
| Field        | Type     | Null | Key | Default | Extra |
+--------------+----------+------+-----+---------+-------+
| login        | int(1)   | NO   |     | NULL    |       |  # 로그인 가능 여부를 설정합니다. (1 = Yes)
| registration | int(1)   | NO   |     | NULL    |       |  # 등록 가능 여부를 설정합니다. (1 = Yes)
| begin_timer  | datetime | YES  |     | NULL    |       |  # CTF 시작 시간을 설정합니다.
| end_timer    | datetime | YES  |     | NULL    |       |  # CTF 종료 시간을 설정합니다.
| game_start   | int(1)   | NO   |     | NULL    |       |  # 게임이 시작 되었으면 1을 저장합니다.
+--------------+----------+------+-----+---------+-------+


mysql> desc logs;
+----------+-------------+------+-----+---------+----------------+
| Field    | Type        | Null | Key | Default | Extra          |
+----------+-------------+------+-----+---------+----------------+
| idx      | int(11)     | NO   | PRI | NULL    | auto_increment |  
| category | text        | NO   |     | NULL    |                |  # 정답 혹은 오답인지 저장됩니다.
| nickname | varchar(14) | NO   |     | NULL    |                |  # 사용자의 닉네임이 저장됩니다.
| submit   | text        | NO   |     | NULL    |                |  # 사용자가 입력한 flag 값이 저장됩니다. (오답 포함)
| title    | text        | NO   |     | NULL    |                |  # 사용자가 입력한 문제의 제목이 저장됩니다.
| date     | datetime    | NO   |     | NULL    |                |  # 해당 로그의 생성 시간이 저장됩니다.
+----------+-------------+------+-----+---------+----------------+


mysql> desc user;
+------------------+--------------+------+-----+---------+-------+
| Field            | Type         | Null | Key | Default | Extra |
+------------------+--------------+------+-----+---------+-------+
| userid           | varchar(100) | NO   |     | NULL    |       |  # 사용자의 ID가 저장됩니다.
| userpw           | text         | NO   |     | NULL    |       |  # 사용자의 PW가 저장됩니다.
| nickname         | text         | NO   |     | NULL    |       |  # 사용자의 nickname이 저장됩니다.
| points           | int(11)      | NO   |     | NULL    |       |  # 사용자의 현재 점수가 저장됩니다.
| admin            | int(11)      | NO   |     | NULL    |       |  # 관리자 여부를 확인 합니다. (1 = admin)
| visible          | int(11)      | NO   |     | NULL    |       |  # 점수판에서 사용자를 출력할 것인지를 확인 합니다.
| comment          | text         | NO   |     | NULL    |       |  # 사용자의 짧은 인사말이 저장됩니다.
| profile          | text         | YES  |     | NULL    |       |  # 사용자가 업로드한 프로필 사진의 경로가 저장 됩니다.
| last_time        | datetime     | YES  |     | NULL    |       |  # 마지막으로 푼 시간이 저장됩니다.
| solved_challenge | text         | YES  |     | NULL    |       |  # 사용자가 푼 문제 리스트가 저장됩니다.
| history          | text         | YES  |     | NULL    |       |  # 어떤 문제를 풀었는지, 몇점을 얻었는지를 저장합니다.
+------------------+--------------+------+-----+---------+-------+

```



------------

#### 5. ETC

기타 버그 및 개선 등과 같은 문의는 whdals7979@gmail.com 으로 부탁드립니다.



