CREATE DATABASE BookStore;
USE BookStore;

CREATE TABLE Book(
    -- BookID varchar(50),

    ISBN varchar(20),
    BookTitle varchar(200),
    Price double(12,2),
    Author varchar(128),
    Type varchar(128),
    Image varchar(128),
    PRIMARY KEY (ISBN)
);

CREATE TABLE Users(
    -- UserID int not null AUTO_INCREMENT,
    UserName varchar(128),
    Password varchar(70),
    PRIMARY KEY (UserName)
);

CREATE TABLE Customer (
    -- CustomerID int not null AUTO_INCREMENT,
    CustomerName varchar(128),
    UserName varchar(128),
    CustomerPhone varchar(12),
    CustomerIC varchar(14),
    CustomerEmail varchar(200),
    CustomerAddress varchar(200),
    CustomerGender varchar(10),
    -- UserID int,
    PRIMARY KEY (UserName),
    CONSTRAINT FOREIGN KEY (UserName) REFERENCES Users(UserName) 
);


CREATE TABLE `Order`(
	OrderID int not null AUTO_INCREMENT,
    UserName varchar(128),
    ISBN varchar(50),
    DatePurchase datetime,
    Quantity int,
    TotalPrice double(12,2),
    Status varchar(1),
    PRIMARY KEY (OrderID),
    CONSTRAINT FOREIGN KEY (ISBN) REFERENCES Book(ISBN) ,
    CONSTRAINT FOREIGN KEY (UserName) REFERENCES Customer(UserName) 
);

CREATE TABLE Cart(
	CartID int not null AUTO_INCREMENT,
    UserName varchar(128),
    ISBN varchar(50),
    Price double(12,2),
    Quantity int,
    TotalPrice double(12,2),
    PRIMARY KEY (CartID),
    CONSTRAINT FOREIGN KEY (ISBN) REFERENCES Book(ISBN) ,
    CONSTRAINT FOREIGN KEY (UserName) REFERENCES Customer(UserName) 
);


INSERT INTO `book`(`ISBN`, `BookTitle`, `Price`, `Author`, `Type`, `Image`) VALUES ('123-456-789-1','Lonely Planet Australia (Travel Guide)',136,'Lonely Planet','Travel','image/travel.jpg');
INSERT INTO `book`(`ISBN`, `BookTitle`, `Price`, `Author`, `Type`, `Image`) VALUES ('123-456-789-2','Crew Resource Management, Second Edition',599,'Barbara Kanki','Technical','image/technical.jpg');
INSERT INTO `book`(`ISBN`, `BookTitle`, `Price`, `Author`, `Type`, `Image`) VALUES ('123-456-789-3','CCNA Routing and Switching 200-125 Official Cert Guide Library',329,'Cisco Press ','Technology','image/technology.jpg');
INSERT INTO `book`(`ISBN`, `BookTitle`, `Price`, `Author`, `Type`, `Image`) VALUES ('123-456-789-4','Easy Vegetarian Slow Cooker Cookbook',75.9,'Rockridge Press','Food','image/food.jpg');
