-- =========================================================
-- TRADITIONAL CRAFT DIRECTORY
-- Complete Database Setup — v3.0 FINAL
-- Includes: All tables + Admin Approval System + Sample Data
-- =========================================================

-- ---------- Reset (safe for first-time import) ----------
DROP DATABASE IF EXISTS craft_directory;
CREATE DATABASE craft_directory CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE craft_directory;

-- =========================================================
-- TABLE 1: REGIONS
-- =========================================================
CREATE TABLE regions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    state_name VARCHAR(100) NOT NULL,
    zone VARCHAR(50) NOT NULL,
    INDEX idx_zone (zone)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =========================================================
-- TABLE 2: CATEGORIES
-- =========================================================
CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category_name VARCHAR(100) NOT NULL,
    slug VARCHAR(120) NOT NULL UNIQUE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =========================================================
-- TABLE 3: CRAFTS
-- =========================================================
CREATE TABLE crafts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    craft_name VARCHAR(150) NOT NULL,
    category_id INT,
    region_id INT,
    description TEXT,
    image VARCHAR(255) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL,
    FOREIGN KEY (region_id) REFERENCES regions(id) ON DELETE SET NULL,
    INDEX idx_craft_category (category_id),
    INDEX idx_craft_region (region_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =========================================================
-- TABLE 4: ARTISANS
-- =========================================================
CREATE TABLE artisans (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    craft_id INT,
    region_id INT,
    experience_years INT DEFAULT 0,
    workshop_address TEXT,
    contact_number VARCHAR(20),
    email VARCHAR(150),
    description TEXT,
    technique TEXT,
    image VARCHAR(255) DEFAULT NULL,
    is_verified TINYINT(1) DEFAULT 0,
    profile_views INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (craft_id) REFERENCES crafts(id) ON DELETE SET NULL,
    FOREIGN KEY (region_id) REFERENCES regions(id) ON DELETE SET NULL,
    INDEX idx_artisan_craft (craft_id),
    INDEX idx_artisan_region (region_id),
    INDEX idx_artisan_verified (is_verified)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =========================================================
-- TABLE 5: ADMINS (approved administrators)
-- =========================================================
CREATE TABLE admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =========================================================
-- TABLE 6: ADMIN_REQUESTS (pending/approved/rejected registrations)
-- =========================================================
CREATE TABLE admin_requests (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    full_name VARCHAR(150) NOT NULL,
    email VARCHAR(150) NOT NULL,
    password VARCHAR(255) NOT NULL,
    status ENUM('pending','approved','rejected') DEFAULT 'pending',
    requested_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    reviewed_at TIMESTAMP NULL,
    reviewed_by VARCHAR(50) NULL,
    remarks VARCHAR(255) NULL,
    INDEX idx_status (status),
    INDEX idx_requested (requested_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =========================================================
-- TABLE 7: ADMIN_ACTIVITY_LOG (approve/reject audit trail)
-- =========================================================
CREATE TABLE admin_activity_log (
    id INT AUTO_INCREMENT PRIMARY KEY,
    action VARCHAR(50) NOT NULL,
    target_username VARCHAR(50) NOT NULL,
    performed_by VARCHAR(50) NOT NULL,
    performed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    details TEXT,
    INDEX idx_action (action),
    INDEX idx_performed (performed_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =========================================================
-- TABLE 8: CONTACTS (public contact form submissions)
-- =========================================================
CREATE TABLE contacts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL,
    subject VARCHAR(200) NOT NULL,
    message TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_created (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =========================================================
-- SAMPLE DATA: REGIONS (12 states across India)
-- =========================================================
INSERT INTO regions (state_name, zone) VALUES
('Rajasthan','North'),
('Gujarat','West'),
('Tamil Nadu','South'),
('West Bengal','East'),
('Uttar Pradesh','North'),
('Odisha','East'),
('Karnataka','South'),
('Kerala','South'),
('Bihar','East'),
('Madhya Pradesh','Central'),
('Assam','North-East'),
('Punjab','North');

-- =========================================================
-- SAMPLE DATA: CATEGORIES
-- =========================================================
INSERT INTO categories (category_name, slug) VALUES
('Textile & Weaving','textile-weaving'),
('Pottery & Ceramics','pottery-ceramics'),
('Metal Craft','metal-craft'),
('Wood Craft','wood-craft'),
('Painting','painting'),
('Jewellery','jewellery'),
('Bamboo & Cane','bamboo-cane');

-- =========================================================
-- SAMPLE DATA: CRAFTS (10 authentic Indian crafts)
-- =========================================================
INSERT INTO crafts (craft_name, category_id, region_id, description) VALUES
('Bandhani Tie-Dye', 1, 2, 'Traditional tie-dye textile art from Kutch, Gujarat, where tiny knots are tied into fabric before dyeing to create intricate patterns. Practiced for over 5000 years, it is one of the oldest forms of resist dyeing in the world.'),
('Blue Pottery', 2, 1, 'Distinctive blue-glazed pottery from Jaipur, Rajasthan. Unlike other pottery, it does not use clay — instead made from quartz stone powder, fuller earth, and natural dyes. Introduced to Jaipur in the 17th century from Persia.'),
('Kanchipuram Silk', 1, 3, 'Premium silk sarees woven in Kanchipuram, Tamil Nadu. Known for their weight, shine, and durability, they are woven from pure mulberry silk with zari (gold thread) borders. Each saree takes 10-20 days to weave.'),
('Dhokra Metal Casting', 3, 4, 'Ancient lost-wax metal casting technique from Bankura, West Bengal. Non-ferrous metal is cast using the wax-thread method, dating back over 4000 years to the Indus Valley Civilization.'),
('Madhubani Painting', 5, 9, 'Folk painting from the Mithila region of Bihar. Traditionally done on walls with natural pigments, it depicts mythological figures, nature, and daily life using geometric patterns and bright colors.'),
('Bamboo Craft', 7, 11, 'Handwoven bamboo products from Assam and North-East India. Bamboo is split into fine strips and woven into baskets, mats, furniture, and decorative items — a sustainable craft passed down through generations.'),
('Pattachitra', 5, 6, 'Traditional cloth-based scroll painting from Raghurajpur, Odisha. Depicts Hindu mythology, especially Vaishnava themes, using natural colors and fine brushwork on treated cloth.'),
('Kundan Jewellery', 6, 1, 'Traditional gemstone jewellery from Rajasthan. Gemstones are set in gold using the Kundan technique — a highly skilled craft involving layering of gold foil over stones, often combined with Meenakari enamel work.'),
('Channapatna Toys', 4, 7, 'Lacquerware wooden toys from Channapatna, Karnataka. Made from soft ivory wood, colored with vegetable dyes, and polished with lacquer using a hand-turned lathe. GI-tagged craft since 2005.'),
('Warli Painting', 5, 2, 'Tribal art from Maharashtra-Gujarat border. Uses simple geometric shapes — circles, triangles, squares — to depict daily life and rituals of the Warli tribe. Traditionally painted with rice paste on mud walls.');

-- =========================================================
-- SAMPLE DATA: ARTISANS (10 verified real-cluster artisans)
-- =========================================================
INSERT INTO artisans (name, craft_id, region_id, experience_years, workshop_address, contact_number, email, description, technique, is_verified) VALUES
('Ramesh Kumar Prajapati', 2, 1, 25,
 'Mohanpura, Jaipur, Rajasthan',
 '+91-9829012345',
 'ramesh.bluepottery@example.com',
 'Third-generation Blue Pottery artisan from Jaipur. Learned the craft from his grandfather and has trained over 40 students. Works with a small family workshop preserving traditional Persian-influenced techniques.',
 'Quartz stone powder, Fuller earth, natural dyes, hand painting',
 1),

('Lakshmi Devi', 1, 2, 18,
 'Bhuj, Kutch, Gujarat',
 '+91-9876543210',
 'lakshmi.bandhani@example.com',
 'Expert in traditional Bandhani tie-dye techniques. Specializes in complex geometric patterns and works with over 200 women artisans across Kutch villages.',
 'Tie-dye with natural colors, hand-knotting',
 1),

('Murugan Silks', 3, 3, 32,
 'Kanchipuram, Tamil Nadu',
 '+91-9445566778',
 'murugan.kanchi@example.com',
 'Master weaver of Kanchipuram silk sarees. Family has been in the weaving trade for over four generations. Specializes in bridal sarees with intricate zari work.',
 'Handloom weaving with pure mulberry silk and zari',
 1),

('Biren Das', 4, 4, 20,
 'Bikna, Bankura, West Bengal',
 '+91-9800112233',
 'biren.dhokra@example.com',
 'Dhokra metal caster preserving ancient tribal art. Works with the traditional lost-wax method passed down through his family for generations.',
 'Lost-wax casting, beeswax threads, non-ferrous metal',
 1),

('Sita Devi', 5, 9, 15,
 'Madhubani, Bihar',
 '+91-9123456780',
 'sita.madhubani@example.com',
 'Madhubani painter using natural pigments. Learned from her mother and specializes in Kohbar and Bharni styles of Madhubani painting.',
 'Natural pigment on handmade paper and cloth',
 1),

('Anil Bora', 6, 11, 12,
 'Sualkuchi, Assam',
 '+91-9864011223',
 'anil.bamboo@example.com',
 'Bamboo craftsman specializing in home decor. Sources bamboo locally and creates baskets, lamps, and modern furniture using traditional weaving patterns.',
 'Hand weaving bamboo strips, natural treatment',
 1),

('Bhaskar Mohapatra', 7, 6, 22,
 'Raghurajpur, Odisha',
 '+91-9437012345',
 'bhaskar.pattachitra@example.com',
 'Pattachitra scroll painter from the heritage village of Raghurajpur. One of the few remaining artists painting traditional Jagannath and Vaishnava themes.',
 'Natural color on cloth scroll, fine brushwork',
 1),

('Kamal Soni', 8, 1, 30,
 'Johari Bazaar, Jaipur',
 '+91-9829011223',
 'kamal.kundan@example.com',
 'Kundan jewellery master. Family has been crafting royal jewellery since the 18th century. Specializes in traditional Rajputana and Mughal designs.',
 'Kundan setting with Meenakari enamel work',
 1),

('Shivanna Gowda', 9, 7, 16,
 'Channapatna, Karnataka',
 '+91-9880011223',
 'shivanna.toys@example.com',
 'Channapatna toy maker using ivory-wood. Uses traditional lacquer turning techniques to create safe, eco-friendly toys for children.',
 'Lacquer turning, natural vegetable dyes',
 1),

('Jivya Soma Mashe', 10, 2, 28,
 'Palghar, Maharashtra',
 '+91-9870011223',
 'jivya.warli@example.com',
 'Warli tribal artist. Has exhibited internationally and is credited with moving Warli art from mud walls to canvas and paper.',
 'Rice paste on mud wall, natural pigments on canvas',
 1);

-- =========================================================
-- SAMPLE DATA: DEFAULT ADMIN
-- Username: admin
-- Password: admin123  (hashed with bcrypt)
-- =========================================================
INSERT INTO admins (username, password) VALUES
('admin', '$2y$10$e0NRzFQRdTLTOoNEjPk6nOqBSPKStqVOcSF0AGXtj9Tv0F4A2FhIe');

-- =========================================================
-- SAMPLE DATA: PENDING ADMIN REQUESTS (for testing approval flow)
-- Password for both: admin123  (hashed)
-- =========================================================
INSERT INTO admin_requests (username, full_name, email, password, status) VALUES
('priya.sharma', 'Priya Sharma', 'priya@craftdirectory.in',
 '$2y$10$e0NRzFQRdTLTOoNEjPk6nOqBSPKStqVOcSF0AGXtj9Tv0F4A2FhIe', 'pending'),
('arun.verma', 'Arun Verma', 'arun@craftdirectory.in',
 '$2y$10$e0NRzFQRdTLTOoNEjPk6nOqBSPKStqVOcSF0AGXtj9Tv0F4A2FhIe', 'pending');

-- =========================================================
-- VERIFY IMPORT
-- =========================================================
SELECT 'Regions' AS table_name, COUNT(*) AS rows_count FROM regions
UNION ALL SELECT 'Categories', COUNT(*) FROM categories
UNION ALL SELECT 'Crafts', COUNT(*) FROM crafts
UNION ALL SELECT 'Artisans', COUNT(*) FROM artisans
UNION ALL SELECT 'Admins', COUNT(*) FROM admins
UNION ALL SELECT 'Admin Requests', COUNT(*) FROM admin_requests
UNION ALL SELECT 'Activity Log', COUNT(*) FROM admin_activity_log
UNION ALL SELECT 'Contacts', COUNT(*) FROM contacts;