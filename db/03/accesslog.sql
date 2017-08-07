CREATE TABLE access(
access_datetime DATETIME COMMENT  'アクセス日時',
user_id INTEGER COMMENT  'ユーザーID',
DATE DATETIME COMMENT  '年月日',
pv INT COMMENT  'ページビュー',
uu INT COMMENT  'ユニークユーザー数'
);

INSERT INTO access(access_datetime,user_id)
VALUES
('2017/01/22 00:11:41', 1), 
('2017/01/22 01:33:24', 3), 
('2017/01/22 04:51:23', 4), 
('2017/01/22 12:33:21', 1), 
('2017/01/22 20:40:13', 2), 
('2017/01/23 03:29:34', 1), 
('2017/01/23 16:31:36', 5),
('2017/01/24 08:29:57', 2), 
('2017/01/24 11:38:29', 2), 
('2017/01/24 13:59:18', 1), 
('2017/01/24 20:38:27', 3), 
('2017/01/24 23:25:11', 3);

SELECT DATE_FORMAT(access_datetime, '%Y-%m-%d') as date, 
COUNT(*) as pv,
COUNT(DISTINCT user_id) as uu
FROM access
GROUP BY DATE_FORMAT(access_datetime, '%Y-%m-%d');