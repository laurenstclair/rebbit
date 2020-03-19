-- Rebbit Database

-- create tables
CREATE TABLE User(
    user_id int not null auto_increment,
    user_name VARCHAR(32) UNIQUE not null,
    password VARCHAR(256) not null,
    email VARCHAR(256),
    image_locaton VARCHAR(256),
    create_date DATE DEFAULT NOW(),
    delete_date DATE,
    primary key (user_id)
);

CREATE TABLE Admin(
    user_id int not null,
    primary key(user_id)
    foreign key(user_id) references User(user_id) ON DELETE CASCADE
);

CREATE TABLE FollowingUser(
    user_id int not null,
    following_user_id int not null,
    primary key (user_id, following_user_id),
    foreign key(user_id) references User(user_id) ON DELETE CASCADE,
    foreign key(following_user_id) references User(user_id) ON DELETE CASCADE
);

CREATE TABLE Knot(
    knot_id int not null auto_increment,
    knot_name VARCHAR(64),
    description VARCHAR(256),
    create_date DATE DEFAULT NOW(),
    delete_date DATE,
    primary key(knot_id)
);

CREATE TABLE KnotAdmin(
    user_id int not null,
    knot_id int not null,
    delete_date DATE,
    primary key(user_id, knot_id),
    foreign key(user_id) references User(user_id) ON DELETE CASCADE,
    foreign key(knot_id) references Knot(knot_id) ON DELETE CASCADE
);

CREATE TABLE FollowingKnot(
    user_id int not null,
    knot_id int not null,
    create_date DATE DEFAULT NOW(),
    primary key(user_id, knot_id),
    foreign key(user_id) references User(user_id) ON DELETE CASCADE,
    foreign key(knot_id) references Knot(knot_id) ON DELETE CASCADE
);

CREATE TABLE BannedFromKnot(
    user_id int not null,
    knot_id int not null,
    primary key(user_id, knot_id),
    foreign key(user_id) references User(user_id) ON DELETE CASCADE,
    foreign key(knot_id) references Knot(knot_id) ON DELETE CASCADE
);

CREATE TABLE Post(
    post_id int not null auto_increment,
    user_id int not null,
    knot_id int not null,
    post_title VARCHAR(256),
    post_body VARCHAR(256),
    image_location VARCHAR(256),
    create_date DATE DEFAULT NOW(),
    primary key(post_id),
    foreign key(user_id) references User(user_id) ON DELETE CASCADE,
    foreign key(knot_id) references Knot(knot_id) ON DELETE CASCADE
);

CREATE TABLE Comment(
    comment_id int not null auto_increment,
    user_id int not null,
    post_id int not null,
    parent_comment_id int,
    comment_body VARCHAR(256),
    create_date DATE DEFAULT NOW(),
    primary key(comment_id),
    foreign key(user_id) references User(user_id) ON DELETE CASCADE,
    foreign key(post_id) references Post(post_id) ON DELETE CASCADE,
    foreign key(parent_comment_id) references Comment(comment_id) ON DELETE CASCADE
);

CREATE TABLE PostVote(
    post_id int not null,
    user_id int not null,
    upvote int,
    primary key(post_id, user_id),
    foreign key(user_id) references User(user_id) ON DELETE CASCADE,
    foreign key(post_id) references Post(post_id) ON DELETE CASCADE   
);

CREATE TABLE CommentVote(
    comment_id int not null,
    user_id int not null,
    upvote int,
    primary key(comment_id, user_id),
    foreign key(user_id) references User(user_id) ON DELETE CASCADE,
    foreign key(comment_id) references Comment(comment_id) ON DELETE CASCADE   
);

-- Users
-- INSERT INTO User(user_name, password, email) VALUES ('unded_turtle', MD5('ilovefrogs'), 'kzlecha@gmail.com');
-- INSERT INTO User(user_name, password, email) VALUES ('froglover97', MD5('ilovefrogs'), 'lauren.d.stclair@gmail.com');

-- Site Admin
-- INSERT INTO Admin VALUES(1);
-- INSERT INTO Admin VALUES(2);


