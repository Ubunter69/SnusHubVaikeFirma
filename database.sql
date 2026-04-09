


CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    category VARCHAR(50), 
    stock INT DEFAULT 0,
    image VARCHAR(255)
);


CREATE TABLE gallery (
    id INT AUTO_INCREMENT PRIMARY KEY,
    image_name VARCHAR(255) NOT NULL,
    description TEXT,
    upload_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(20) NOT NULL
);


INSERT INTO products (name, description, price, category, stock, image) VALUES
('Siberia White Dry', 'Väga kange valge snus Rootsist. Piparmündi maitse.', 6.50, 'Väga kange', 50, 'siberiawhite.jpg'),
('General Classic', 'Klassikaline Rootsi snus tubaka maitsega.', 5.90, 'Tubakas', 35, 'general.jpg'),
('Lyft Ice Cool', 'Nikotiinipadjad ilma tubakata. Jää ja piparmündi maitse.', 7.20, 'Nikotiinipadjad', 100, 'lyftice.jpg'),
('Oden\'s Extreme', 'Ekstreemselt kange snus. Ainult kogenud kasutajale.', 6.80, 'Kange', 20, 'odensextreme.jpg'),
('Velo Citrus', 'Nikotiinipadjad tsitruse maitsega.', 7.00, 'Nikotiinipadjad', 45, 'velo.jpg');


INSERT INTO gallery (image_name, description) VALUES
('siberiawhite.jpg', 'Siberia White Dry - populaarne valik'),
('general.jpg', 'General Classic - Rootsi klassika'),
('lyftice.jpg', 'Lyft Ice Cool - tubakavaba värskus'),
('odensextreme.jpg', 'Odens Extreme - kange ja täidlane'),
('velo.jpg', 'Velo - kaasaegne nikotiinipadi');
