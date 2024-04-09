CREATE DATABASE IF NOT EXISTS learningPathDB;

CREATE USER 'admin'@'localhost' IDENTIFIED BY 'Password123!';

GRANT SELECT, INSERT, UPDATE, DELETE ON learningPathDB.* TO 'admin'@'localhost';

FLUSH PRIVILEGES; 

USE learningPathDB;


-- Create & populate 'users' table

CREATE TABLE users (
	user_id INT AUTO_INCREMENT PRIMARY KEY,
	username VARCHAR(25) NOT NULL UNIQUE,
	password VARCHAR(255) NOT NULL,
	email VARCHAR(50) NOT NULL UNIQUE,
	bio TEXT,
	picture VARCHAR(50),
	is_expert TINYINT(1) NOT NULL DEFAULT 0,
	created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
	);
	
INSERT INTO users (
	username, password, email, bio, picture, is_expert
	) 
VALUES 
	('johndoe', '$2y$10$mgquLWO5kCInCbgVIktdfOqZxd8OSBD7.mUW5BnlNfOqZCs64eXIq', 'john.doe@yahoo.com', 'Student with a passion for learning', '/assets/images/user1.jpg', 0),
	('janedoe', '$2y$10$aIxxw8qCM0SQSRmSxZFxx.ATKJf7cwh19yzdFZBoAa4.8rduxhw.G', 'jane.doe@hotmail.com', 'Curious scholar seeking a finer understanding of the human experience', '/assets/images/user2.jpg', 1),
	('mikebaker', '$2y$10$f0vT7qv4rIQ1U7fxASHI9OFWzEEW/v/0cHohOwYSGBsxEKq4xWZGC', 'mike.baker@hotmail.com', 'Engineering student with an interest in physics', '/assets/images/user3.jpg', 0),
	('sarahconnor', '$2y$10$B1ai712ILzJOtFD0V8bPKO8Kbx01gCtGq5U5aaOJpLf5hnREc2bom', 'sarah.connor@gmail.com', 'Dr. Sarah Connor, I can be reached at sarah.connor@example.com', '/assets/images/user4.jpg', 1),
	('alexsmith', '$2y$10$r8/qPIm71BCswBR5ecyEbeKd02YfCEbUsrdA.xaliMePs7t47GXyC', 'alex.smith@outlook.com', 'History major with a vested interest in the future', '/assets/images/user5.jpg', 0),
	('emilyjones', '$2y$10$D8TrkcS75GkNnHELLi/3HevlshP/zlSV2P/VeoO9H5SSwmNSRmmRa', 'emily.jones@gmail.com', 'Modern day Shakespeare', '/assets/images/user6.jpg', 0),
	('davidlee', '$2y$10$8kYfEhnC58yuYhQDP9A/Fuilh/JCPKmW52rTK3a2jHw9UlnTT07Xy', 'david.lee@outlook.com', 'Something something, knowledge', '/assets/images/user7.jpg', 1),
	('lucybrown', '$2y$10$iod2fp/FwWV93U.pqAXTmeg/tZUpCHwpKk8YyaPCxGNhl2ThYkzua', 'lucy.brown@hotmail.com', 'Live a lot :)', '/assets/images/user8.jpg', 0),
	('chrisdavis', '$2y$10$X8s3S6guPndHhLUeadOpOuHjq0d5dbgYuHkvfiePne0PrCNkQQrja', 'chris.davis@outlook.com', 'Guru of sorts.. I think', '/assets/images/user9.jpg', 1),
	('lisawilliams', '$2y$10$PkIqf2ZsVd4uzZ5Knsa77ueP90MWXIqNIfAplpWjEpTi3mxidmuiG', 'lisa.williams@hotmail.com', 'Knowledge is the key to advancement!', '/assets/images/user10.jpg', 0);



-- Create & populate 'categories' table

CREATE TABLE categories (
	category_id INT AUTO_INCREMENT PRIMARY KEY,
	category_name VARCHAR(25) NOT NULL,
	created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
	);
	
INSERT INTO categories (
	category_name
	) 
VALUES 
	('Technology'),
	('Science'),
	('Mathematics'),
	('Arts'),
	('History'),
	('Literature'),
	('Engineering'),
	('Medicine'),
	('Business'),
	('Psychology');

	
	
-- Create & populate 'learning_paths' table

CREATE TABLE learning_paths (
	learning_path_id INT AUTO_INCREMENT PRIMARY KEY,
	user_id INT NOT NULL,
	title VARCHAR(50) NOT NULL UNIQUE,
	description TEXT NOT NULL,
	category_id INT NOT NULL,
	is_expert_certified TINYINT(1) NOT NULL DEFAULT 0,
	FOREIGN KEY (user_id) REFERENCES users(user_id),
	FOREIGN KEY (category_id) REFERENCES categories(category_id),
	created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
	);
	
INSERT INTO learning_paths (
	user_id, title, description, category_id, is_expert_certified
	) 
VALUES 
	(1, 'Intro to Python', 'Learn Python basics', 1, 0),
	(2, 'World History Overview', 'Explore major events in world history', 5, 0),
	(3, 'Basics of Economics', 'Introduction to economic principles', 9, 0),
	(4, 'Essentials of Marketing', 'Fundamentals of marketing strategies', 9, 0),
	(5, 'Beginner’s Guide to Photography', 'Photography techniques for beginners', 4, 0),
	(6, 'Contemporary Art Appreciation', 'Understanding contemporary art', 4, 1),
	(7, 'Introduction to Philosophy', 'Exploring philosophical concepts', 10, 0),
	(8, 'Yoga for Beginners', 'Yoga practices for health and wellness', 8, 0),
	(9, 'Creative Writing Workshop', 'Developing writing skills in various genres', 6, 0),
	(10, 'Basic First Aid Training', 'Fundamentals of first aid and emergency response', 8, 0),
	(1, 'Fundamentals of Mathematics', 'Understanding basic mathematical concepts', 3, 1),
	(2, 'Elementary French', 'Learning basic French language and grammar', 6, 0),
	(3, 'Digital Art Techniques', 'Creating art using digital tools', 4, 0),
	(4, 'Introduction to Astrophysics', 'Exploring the basics of astrophysics', 2, 1),
	(5, 'Sustainable Living Practices', 'Principles of sustainability and eco-friendly living', 2, 0);



-- Create & populate 'votes' table

CREATE TABLE votes (
	vote_id INT AUTO_INCREMENT PRIMARY KEY,
	vote_type ENUM('upvote','downvote') NOT NULL,
	learning_path_id INT NOT NULL,
	user_id INT NOT NULL,
	FOREIGN KEY (learning_path_id) REFERENCES learning_paths(learning_path_id),
	FOREIGN KEY (user_id) REFERENCES users(user_id),
	created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
	);
	
INSERT INTO votes (
	vote_type, learning_path_id, user_id
	) 
VALUES 
	('upvote', 1, 2),
	('downvote', 1, 3),
	('upvote', 2, 1),
	('upvote', 2, 4),
	('downvote', 3, 5),
	('upvote', 1, 1),
	('upvote', 1, 4),
	('upvote', 1, 5),
	('upvote', 1, 6),
	('downvote', 1, 7),
	('upvote', 1, 8),
	('upvote', 1, 9),
	('upvote', 2, 2),
	('downvote', 2, 3),
	('upvote', 2, 5),
	('upvote', 2, 6),
	('upvote', 2, 7),
	('downvote', 2, 8),
	('upvote', 3, 1),
	('upvote', 3, 2),
	('upvote', 3, 2),
	('downvote', 3, 3),
	('upvote', 3, 4),
	('upvote', 3, 6),
	('upvote', 3, 7),
	('upvote', 3, 8),
	('upvote', 3, 9),
	('upvote', 4, 1),
	('upvote', 4, 2),
	('upvote', 4, 3),
	('upvote', 4, 4),
	('downvote', 4, 5),
	('downvote', 4, 6),
	('upvote', 4, 7),
	('upvote', 4, 8),
	('upvote', 4, 9),
	('upvote', 4, 10),
	('upvote', 5, 1),
	('upvote', 5, 2),
	('upvote', 5, 3),
	('downvote', 5, 4),
	('upvote', 5, 5),
	('upvote', 5, 6),
	('downvote', 5, 7),
	('upvote', 5, 8),
	('upvote', 5, 9),
	('upvote', 5, 10),
	('upvote', 6, 1),
	('upvote', 6, 2),
	('upvote', 6, 3),
	('upvote', 6, 4),
	('upvote', 6, 5),
	('upvote', 6, 6),
	('upvote', 6, 7),
	('downvote', 6, 8),
	('upvote', 6, 9),
	('downvote', 7, 1),
	('upvote', 7, 2),
	('upvote', 7, 3),
	('upvote', 7, 4),
	('upvote', 7, 5),
	('upvote', 7, 6),
	('upvote', 7, 7),
	('upvote', 7, 8),
	('upvote', 7, 9),
	('upvote', 7, 10),
	('upvote', 8, 1),
	('upvote', 8, 2),
	('downvote', 8, 3),
	('upvote', 8, 4),
	('upvote', 8, 5),
	('downvote', 8, 6),
	('upvote', 8, 7),
	('upvote', 8, 8),
	('upvote', 8, 9),
	('upvote', 8, 10),
	('upvote', 9, 1),
	('upvote', 9, 2),
	('upvote', 9, 3),
	('upvote', 9, 4),
	('upvote', 9, 5),
	('upvote', 9, 6),
	('upvote', 9, 7),
	('downvote', 9, 8),
	('upvote', 9, 9),
	('upvote', 10, 1),
	('upvote', 10, 2),
	('downvote', 10, 3),
	('upvote', 10, 4),
	('upvote', 10, 5),
	('upvote', 10, 6),
	('upvote', 10, 7),
	('upvote', 10, 8),
	('downvote', 10, 9),
	('upvote', 11, 1),
	('upvote', 11, 2),
	('upvote', 11, 3),
	('downvote', 11, 4),
	('upvote', 11, 5),
	('upvote', 11, 6),
	('upvote', 11, 7),
	('upvote', 11, 8),
	('upvote', 11, 9),
	('upvote', 11, 10),
	('upvote', 12, 1),
	('upvote', 12, 2),
	('upvote', 12, 3),
	('upvote', 12, 4),
	('upvote', 12, 5),
	('upvote', 12, 6),
	('upvote', 12, 7),
	('upvote', 13, 1),
	('upvote', 13, 2),
	('upvote', 13, 3),
	('upvote', 13, 4),
	('upvote', 13, 5),
	('upvote', 13, 6),
	('downvote', 13, 7),
	('upvote', 13, 8),
	('upvote', 13, 9),
	('upvote', 13, 10),
	('upvote', 14, 1),
	('upvote', 14, 2),
	('upvote', 14, 3),
	('upvote', 14, 4),
	('upvote', 14, 5),
	('upvote', 14, 6),
	('upvote', 14, 7),
	('downvote', 14, 8),
	('downvote', 14, 9),
	('upvote', 14, 10),
	('upvote', 15, 1),
	('upvote', 15, 2),
	('upvote', 15, 3),
	('upvote', 15, 4),
	('upvote', 15, 5),
	('upvote', 15, 6),
	('upvote', 15, 7),
	('upvote', 15, 8),
	('upvote', 15, 9),
	('downvote', 15, 10);



-- Create & populate 'resource_types' table

CREATE TABLE resource_types (
	resource_type_id INT AUTO_INCREMENT PRIMARY KEY,
	resource_type_name VARCHAR(25) NOT NULL UNIQUE,
	created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
	);

INSERT INTO resource_types (
	resource_type_name
	) 
VALUES 
	('Video'),
	('Article'),
	('E-Book'),
	('Online Course'),
	('Podcast'),
	('Webinar'),
	('Workshop'),
	('Tutorial'),
	('Lecture'),
	('Case Study');



-- Create & populate 'resources' table

CREATE TABLE resources (
	resource_id INT AUTO_INCREMENT PRIMARY KEY,
	learning_path_id INT NOT NULL,
	resource_type_id INT NOT NULL,
	url VARCHAR(255) NOT NULL,
	resource_description VARCHAR(100) NOT NULL,
	FOREIGN KEY (learning_path_id) REFERENCES learning_paths(learning_path_id),
	FOREIGN KEY (resource_type_id) REFERENCES resource_types(resource_type_id),
	created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
	);

INSERT INTO resources (
	learning_path_id, resource_type_id, url, resource_description
	) 
VALUES 
	(1, 1, 'https://www.youtube.com/watch?v=eWRfhZUzrAc', 'Introductory Video on Python'),
	(2, 2, 'https://www.britannica.com/browse/World-History', 'Article on World History Highlights'),
	(3, 3, 'https://open.umn.edu/opentextbooks/textbooks/32', 'Economics for Beginners eBook'),
	(4, 1, 'https://www.youtube.com/watch?v=4ajmfzj9G1g', 'Video on Marketing Strategies'),
	(5, 2, 'https://the-photo-ebook.com/', 'Photography Tips for Beginners'),
	(6, 3, 'https://www.kobo.com/ca/en/ebook/contemporary-art', 'Contemporary Art Explained'),
	(7, 9, 'https://www.google.com/search?channel=fs&client=ubuntu-sn&q=philosofy+lecture#fpstate=ive&vld=cid:41a8501f,vid:tY2njfpWC8g,st:0', 'Philosophy Lecture Series'),
	(8, 8, 'https://www.youtube.com/watch?v=v7AYKMP6rOE', 'Yoga Basics for Wellness'),
	(9, 10, 'https://www.nawe.co.uk/DB/current-wip-edition-2/articles/critical-approaches-to-creative-writing-a-case-study.html', 'Creative Writing Techniques Case Study'),
	(10, 1, 'https://www.youtube.com/watch?v=5OKFljZ2GQE', 'First Aid Training Session'),
	(11, 2, 'https://www.niu.edu/mathmatters/everyday-life/index.shtml', 'Mathematics in Daily Life'),
	(12, 3, 'https://easyreaders.org/product-category/french/', 'French Language Learning Guide'),
	(13, 1, 'https://www.youtube.com/watch?v=0RmGV5wALG0', 'Digital Art for Beginners'),
	(14, 2, 'https://www.annualreviews.org/journal/astro', 'Astrophysics: An Overview'),
	(15, 3, 'https://refillexpress.com/ebook/', 'Sustainable Living Guide');



-- Create & populate 'expert_certifications' table

CREATE TABLE expert_certifications (
	certification_id INT AUTO_INCREMENT PRIMARY KEY,
	learning_path_id INT NOT NULL,
	user_id INT NOT NULL,
	date_certified TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
	FOREIGN KEY (learning_path_id) REFERENCES learning_paths(learning_path_id),
	FOREIGN KEY (user_id) REFERENCES users(user_id),
	created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
	);
	
INSERT INTO expert_certifications (
	learning_path_id, user_id
	) 
VALUES 
	(6, 2),
	(11, 7),
	(14, 9);

