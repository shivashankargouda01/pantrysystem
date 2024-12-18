-- Create the database
CREATE DATABASE IF NOT EXISTS community_pantry;
USE community_pantry;

-- Table for subsystems
CREATE TABLE IF NOT EXISTS subsystems (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE
);

-- Table for communities
CREATE TABLE IF NOT EXISTS communities (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    subsystem_id INT NOT NULL,
    city VARCHAR(100) NOT NULL,
    FOREIGN KEY (subsystem_id) REFERENCES subsystems(id) ON DELETE CASCADE
);

-- Table for needs
CREATE TABLE IF NOT EXISTS needs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    community_id INT NOT NULL,
    item_name VARCHAR(100) NOT NULL,
    quantity INT NOT NULL,
    status ENUM('Pending', 'Fulfilled') DEFAULT 'Pending',
    FOREIGN KEY (community_id) REFERENCES communities(id) ON DELETE CASCADE
);

-- Table for donations
CREATE TABLE IF NOT EXISTS donations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    community_id INT NOT NULL,
    donor_name VARCHAR(100) NOT NULL,
    item_name VARCHAR(100) NOT NULL,
    quantity INT NOT NULL,
    reward_given BOOLEAN DEFAULT 0,
    FOREIGN KEY (community_id) REFERENCES communities(id) ON DELETE CASCADE
);

-- Insert data for subsystems
INSERT INTO subsystems (name) VALUES
('Orphan Children'),
('Old Age People'),
('Physically Disabled People');

-- Insert example data for communities
INSERT INTO communities (name, subsystem_id, city) VALUES
('Happy Orphanage', 1, 'City A'),
('Sunrise Old Age Home', 2, 'City B'),
('Care for Disabled', 3, 'City A');

-- Insert example needs for communities
INSERT INTO needs (community_id, item_name, quantity) VALUES
(1, 'Toys', 10),
(1, 'Books', 20),
(2, 'Wheelchairs', 5),
(3, 'Crutches', 8);
