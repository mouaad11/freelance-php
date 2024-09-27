-- Create a new database for the integrated system
CREATE DATABASE IF NOT EXISTS freelance;

-- Switch to the new database
USE freelance;

-- Table: users
CREATE TABLE IF NOT EXISTS users (
  UserID INT PRIMARY KEY AUTO_INCREMENT,
  Username VARCHAR(50) UNIQUE NOT NULL,
  Password VARCHAR(255) NOT NULL,
  Role ENUM('admin', 'boss', 'client') DEFAULT 'client',
  Email VARCHAR(100) UNIQUE NOT NULL,
  DateCreated TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP(),
  ProfileImageURL VARCHAR(255) NOT NULL,
  PhoneNum VARCHAR(10),
  Status VARCHAR(255) NOT NULL,
  AcceptedProjectsCount INT DEFAULT 0,
  DeclinedProjectsCount INT DEFAULT 0,
  ReportNumber INT DEFAULT 0
);

-- Table: user_chat
CREATE TABLE IF NOT EXISTS user_chat (
  user_id INT PRIMARY KEY,
  unique_id INT,
  fname VARCHAR(255) NOT NULL,
  lname VARCHAR(255) NOT NULL,
  email VARCHAR(255) NOT NULL,
  password VARCHAR(255) NOT NULL,
  img VARCHAR(255) NOT NULL,
  status VARCHAR(255) NOT NULL
);

-- Table: publications
CREATE TABLE IF NOT EXISTS publications (
  PublicationID INT PRIMARY KEY AUTO_INCREMENT,
  ClientID INT NOT NULL,
  Title VARCHAR(255) NOT NULL,
  Description TEXT NOT NULL,
  Price DECIMAL(10,2) NOT NULL,
  Category ENUM('webdev','design','mobile'), -- Fixed typo in 'design' ENUM
  MaxDate TIMESTAMP NOT NULL,
  DateAdded TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP(),
  FOREIGN KEY (ClientID) REFERENCES users (UserID)
);

-- Table: work
CREATE TABLE IF NOT EXISTS work (
  WorkID INT PRIMARY KEY AUTO_INCREMENT,
  PublicationID INT NOT NULL,
  DeveloperID INT NOT NULL,
  WorkLink VARCHAR(255),
  Status ENUM('Pending', 'Approved', 'Rejected') DEFAULT 'Pending',
  DateSubmitted TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP(),
  FOREIGN KEY (PublicationID) REFERENCES publications (PublicationID),
  FOREIGN KEY (DeveloperID) REFERENCES users (UserID)
);

-- Table: reports
CREATE TABLE IF NOT EXISTS reports (
  ReportID INT PRIMARY KEY AUTO_INCREMENT,
  ReporterID INT NOT NULL,
  ReportedPublicationID INT,
  ReportedUserID INT,
  Reason TEXT NOT NULL,
  DateReported TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP(),
  FOREIGN KEY (ReporterID) REFERENCES users (UserID),
  FOREIGN KEY (ReportedPublicationID) REFERENCES publications (PublicationID),
  FOREIGN KEY (ReportedUserID) REFERENCES users (UserID)
);

-- Table: messages
CREATE TABLE IF NOT EXISTS messages (
  msg_id INT PRIMARY KEY AUTO_INCREMENT,
  incoming_msg_id INT NOT NULL,
  outgoing_msg_id INT NOT NULL,
  msg VARCHAR(1000) NOT NULL
);

-- Table: user_interactions
CREATE TABLE IF NOT EXISTS user_interactions (
  user_id_1 INT NOT NULL,
  user_id_2 INT NOT NULL,
  PRIMARY KEY (user_id_1, user_id_2),
  KEY user_id_2 (user_id_2)
);

-- Table: images
CREATE TABLE IF NOT EXISTS images (
  ImageID INT PRIMARY KEY AUTO_INCREMENT,
  PublicationID INT NOT NULL,
  ImageURL VARCHAR(255) NOT NULL,
  DateAdded TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP(),
  FOREIGN KEY (PublicationID) REFERENCES publications (PublicationID)
);
