INSERT INTO final_user(user_email, user_password, user_first, user_last)
VALUES('test@pitt.edu', 'test', 'test', 'test');

INSERT INTO final_user(user_email, user_password, user_first, user_last)
VALUES('agh34@pitt.edu', 'pass', 'Anthony', 'Harris');

INSERT INTO final_user(user_email, user_password, user_first, user_last)
VALUES('bot@pitt.edu', 'bot', 'Beep', 'Boop');

select * from final_user;

INSERT INTO final_type(type_name)
VALUES('Not Friend');

INSERT INTO final_type(type_name)
VALUES('Pending');

INSERT INTO final_type(type_name)
VALUES('Friend');

select * from final_type;

INSERT INTO final_relationship(first_id, second_id, type_id)
VALUES('1', '2', 3);

INSERT INTO final_relationship(first_id, second_id, type_id)
VALUES('1', '3', 3);

select * from final_relationship;

SELECT user_id, user_first, user_last
FROM final_user a, final_relationship b
WHERE b.first_id = a.user_id AND b.second_id = a.user_id; 

INSERT INTO final_event(event_name, event_location, event_date, user_id)
VALUES('Wing Night', 'William Penn Tavern', '2018-04-18', 2);

INSERT INTO final_event(event_name, event_location, event_date, user_id)
VALUES('Karaoke', 'Cappy\'s', '2018-04-20', 1);

INSERT INTO final_event(event_name, event_location, event_date, user_id)
VALUES('$2 Well Drinks', 'Stack\'d', '2018-04-19', 3);

select * from final_event;

SELECT e.*, p.user_first, p.user_last
FROM final_event e JOIN final_user p
ON e.user_id = p.user_id
WHERE e.user_id IN (SELECT p.user_id
					FROM final_user p, final_relationship r 
					WHERE r.second_id = p.user_id AND r.type_id = 3);
                    
SELECT user_id, user_first, user_last
FROM final_user
WHERE user_id <> 1;

INSERT INTO final_category(category_type)
VALUES('Yes');

INSERT INTO final_category(category_type)
VALUES('No');

INSERT INTO final_category(category_type)
VALUES('Maybe');

select * from final_category;

SELECT user_id, user_first, user_last
FROM final_user
WHERE user_id IN (SELECT r.first_id
				FROM final_user p, final_relationship r 
                WHERE r.second_id = 6)
OR user_id IN (SELECT r.second_id
				FROM final_user p, final_relationship r 
                WHERE r.first_id = 6);
                
select * from final_response;

SELECT COUNT(*)
FROM final_response
WHERE event_id = 4 AND category_id = 1;

select * from final_group;

INSERT INTO final_user_group(group_id, user_id)
VALUES(1, 5);

select * from final_user_group;

select * from final_group 
where user_id = 6 and group_name = 'Best Friend' 
limit 1;

INSERT INTO final_user_group(group_id, user_id)
VALUES(1, 5);

select a.user_id, user_first, user_last
from final_user a join final_user_group b
on a.user_id = b.user_id
where group_id = 13;

SELECT user_id, user_first, user_last
FROM final_user 
WHERE (user_id IN (SELECT r.first_id
				FROM final_user p, final_relationship r 
                WHERE r.second_id = 6)
OR user_id IN (SELECT r.second_id
				FROM final_user p, final_relationship r 
                WHERE r.first_id = 6))
AND user_id NOT IN (SELECT u.user_id
						FROM final_user u JOIN final_user_group g
						ON u.user_id = g.user_id
						WHERE group_id = 13);
                        
SELECT group_id, group_name
                    FROM final_group
                    WHERE user_id = 6;
				
DELETE FROM final_relationship
WHERE first_id = 7 AND second_id = 6;