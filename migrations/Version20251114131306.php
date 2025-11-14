<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Migration pour les entités MODUSCAP : ProductCategory, Product, ProductOptionGroup, ProductOption, ProductOptionValue, ProductImage
 */
final class Version20251114131306 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Création des entités MODUSCAP : catégories de produits, produits, groupes d\'options, options, valeurs d\'options et images de produits';
    }

    public function up(Schema $schema): void
    {
        // Table product_categories
        $this->addSql('CREATE TABLE product_categories (
            id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, 
            slug VARCHAR(100) NOT NULL, 
            name VARCHAR(100) NOT NULL, 
            description TEXT DEFAULT NULL, 
            color VARCHAR(50) DEFAULT NULL, 
            is_active BOOLEAN NOT NULL, 
            sort_order INTEGER NOT NULL, 
            position INTEGER NOT NULL, 
            created_at DATETIME NOT NULL, 
            updated_at DATETIME DEFAULT NULL
        )');

        // Table products
        $this->addSql('CREATE TABLE products (
            id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, 
            category_id INTEGER NOT NULL, 
            slug VARCHAR(100) NOT NULL, 
            name VARCHAR(100) NOT NULL, 
            short_description TEXT DEFAULT NULL, 
            description TEXT DEFAULT NULL, 
            features TEXT DEFAULT NULL, 
            specifications TEXT DEFAULT NULL, 
            price DECIMAL(10, 2) NOT NULL, 
            price_per_square_meter DECIMAL(10, 2) DEFAULT NULL, 
            surface DECIMAL(5, 2) DEFAULT NULL, 
            dimensions VARCHAR(50) DEFAULT NULL, 
            assembly_time VARCHAR(20) DEFAULT NULL, 
            energy_class VARCHAR(50) DEFAULT NULL, 
            construction_type VARCHAR(50) DEFAULT NULL, 
            rooms INTEGER DEFAULT NULL, 
            bathrooms INTEGER DEFAULT NULL, 
            bedrooms INTEGER DEFAULT NULL, 
            terrace DECIMAL(5, 2) DEFAULT NULL, 
            floor_height DECIMAL(5, 2) DEFAULT NULL, 
            warranty_structure VARCHAR(20) DEFAULT NULL, 
            warranty_equipment VARCHAR(20) DEFAULT NULL, 
            is_active BOOLEAN NOT NULL, 
            is_featured BOOLEAN NOT NULL, 
            is_pre_order BOOLEAN NOT NULL, 
            stock_quantity INTEGER NOT NULL, 
            sort_order INTEGER NOT NULL, 
            views INTEGER NOT NULL, 
            sales INTEGER NOT NULL, 
            created_at DATETIME NOT NULL, 
            updated_at DATETIME DEFAULT NULL, 
            CONSTRAINT FK_D34A04AD12469DE2 FOREIGN KEY (category_id) REFERENCES product_categories (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        )');

        // Table product_option_groups
        $this->addSql('CREATE TABLE product_option_groups (
            id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, 
            slug VARCHAR(100) NOT NULL, 
            name VARCHAR(100) NOT NULL, 
            description TEXT DEFAULT NULL, 
            type VARCHAR(50) NOT NULL, 
            is_required BOOLEAN NOT NULL, 
            is_active BOOLEAN NOT NULL, 
            sort_order INTEGER NOT NULL, 
            min_selections INTEGER DEFAULT NULL, 
            max_selections INTEGER DEFAULT NULL, 
            created_at DATETIME NOT NULL, 
            updated_at DATETIME DEFAULT NULL
        )');

        // Table product_options
        $this->addSql('CREATE TABLE product_options (
            id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, 
            group_id INTEGER NOT NULL, 
            slug VARCHAR(100) NOT NULL, 
            name VARCHAR(100) NOT NULL, 
            description TEXT DEFAULT NULL, 
            value VARCHAR(100) DEFAULT NULL, 
            price DECIMAL(10, 2) DEFAULT NULL, 
            price_percentage DECIMAL(10, 2) DEFAULT NULL, 
            is_default BOOLEAN NOT NULL, 
            is_active BOOLEAN NOT NULL, 
            sort_order INTEGER NOT NULL, 
            color VARCHAR(20) DEFAULT NULL, 
            created_at DATETIME NOT NULL, 
            updated_at DATETIME DEFAULT NULL, 
            CONSTRAINT FK_A9C6AE9FFE54D947 FOREIGN KEY (group_id) REFERENCES product_option_groups (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        )');

        // Table product_option_values
        $this->addSql('CREATE TABLE product_option_values (
            id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, 
            product_id INTEGER NOT NULL, 
            option_id INTEGER NOT NULL, 
            custom_value VARCHAR(255) DEFAULT NULL, 
            price DECIMAL(10, 2) DEFAULT NULL, 
            price_percentage DECIMAL(10, 2) DEFAULT NULL, 
            is_selected BOOLEAN NOT NULL, 
            sort_order INTEGER NOT NULL, 
            created_at DATETIME NOT NULL, 
            updated_at DATETIME DEFAULT NULL, 
            CONSTRAINT FK_7B7F3A7A4584665A FOREIGN KEY (product_id) REFERENCES products (id) NOT DEFERRABLE INITIALLY IMMEDIATE,
            CONSTRAINT FK_7B7F3A7A5A35A3D7 FOREIGN KEY (option_id) REFERENCES product_options (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        )');

        // Table product_images
        $this->addSql('CREATE TABLE product_images (
            id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, 
            product_id INTEGER DEFAULT NULL, 
            media_id INTEGER NOT NULL, 
            title VARCHAR(100) DEFAULT NULL, 
            description TEXT DEFAULT NULL, 
            alt VARCHAR(50) DEFAULT NULL, 
            sort_order INTEGER NOT NULL, 
            is_main BOOLEAN NOT NULL, 
            is_active BOOLEAN NOT NULL, 
            image_type VARCHAR(20) DEFAULT NULL, 
            created_at DATETIME NOT NULL, 
            updated_at DATETIME DEFAULT NULL, 
            CONSTRAINT FK_64617F3A4584665A FOREIGN KEY (product_id) REFERENCES products (id) NOT DEFERRABLE INITIALLY IMMEDIATE,
            CONSTRAINT FK_64617F3A1136BE75 FOREIGN KEY (media_id) REFERENCES media (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        )');

        // Table pour les traductions (translatable)
        $this->addSql('CREATE TABLE ext_translations (
            id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, 
            locale VARCHAR(8) NOT NULL, 
            foreign_key VARCHAR(32) NOT NULL, 
            field VARCHAR(32) NOT NULL, 
            content TEXT DEFAULT NULL
        )');

        // Index pour product_categories
        $this->addSql('CREATE UNIQUE INDEX UNIQ_A2BBD2A9A7B643 ON product_categories (slug)');
        $this->addSql('CREATE INDEX IDX_A2BBD2A944F5D008 ON product_categories (sort_order)');
        $this->addSql('CREATE INDEX IDX_A2BBD2A9F7BDC3DE ON product_categories (position)');

        // Index pour products
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D34A04AD12469DE2 ON products (slug)');
        $this->addSql('CREATE INDEX IDX_D34A04AD444F5D008 ON products (sort_order)');
        $this->addSql('CREATE INDEX IDX_D34A04AD6C75553A ON products (views)');
        $this->addSql('CREATE INDEX IDX_D34A04AD5B465CF0 ON products (sales)');
        $this->addSql('CREATE INDEX IDX_D34A04AD2C2920D9 ON products (price)');
        $this->addSql('CREATE INDEX IDX_D34A04ADBC09B240 ON products (surface)');
        $this->addSql('CREATE INDEX IDX_D34A04ADE17203CE ON products (energy_class)');
        $this->addSql('CREATE INDEX IDX_D34A04AD5F5E6C01 ON products (category_id)');

        // Index pour product_option_groups
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8B6158E8A7B643 ON product_option_groups (slug)');
        $this->addSql('CREATE INDEX IDX_8B6158E844F5D008 ON product_option_groups (sort_order)');
        $this->addSql('CREATE INDEX IDX_8B6158E8F50A5A30 ON product_option_groups (type)');

        // Index pour product_options
        $this->addSql('CREATE UNIQUE INDEX UNIQ_A9C6AE9FE54D947 ON product_options (slug)');
        $this->addSql('CREATE INDEX IDX_A9C6AE9F444F5D008 ON product_options (sort_order)');
        $this->addSql('CREATE INDEX IDX_A9C6AE9FC16F07C0 ON product_options (group_id)');
        $this->addSql('CREATE INDEX IDX_A9C6AE9F5EDBF2F8 ON product_options (is_active)');

        // Index pour product_option_values
        $this->addSql('CREATE UNIQUE INDEX UNIQ_7B7F3A7A4584665A5A35A3D7 ON product_option_values (product_id, option_id)');
        $this->addSql('CREATE INDEX IDX_7B7F3A7A5A35A3D7 ON product_option_values (option_id)');
        $this->addSql('CREATE INDEX IDX_7B7F3A7A4584665A ON product_option_values (product_id)');
        $this->addSql('CREATE INDEX IDX_7B7F3A7A7B8C7B02 ON product_option_values (is_selected)');

        // Index pour product_images
        $this->addSql('CREATE INDEX IDX_64617F3A4584665A ON product_images (product_id)');
        $this->addSql('CREATE INDEX IDX_64617F3A1136BE75 ON product_images (media_id)');
        $this->addSql('CREATE INDEX IDX_64617F3A4F7BDC3D ON product_images (sort_order)');
        $this->addSql('CREATE INDEX IDX_64617F3A5A35A3D7 ON product_images (is_main)');
        $this->addSql('CREATE INDEX IDX_64617F3A5F5E6C01 ON product_images (is_active)');
        $this->addSql('CREATE INDEX IDX_64617F3AB91C1F69 ON product_images (image_type)');

        // Index pour les traductions
        $this->addSql('CREATE INDEX idx_translation ON ext_translations (locale, foreign_key, field)');
    }

    public function down(Schema $schema): void
    {
        // Suppression des tables dans l'ordre inverse des dépendances
        $this->addSql('DROP TABLE ext_translations');
        $this->addSql('DROP TABLE product_images');
        $this->addSql('DROP TABLE product_option_values');
        $this->addSql('DROP TABLE product_options');
        $this->addSql('DROP TABLE product_option_groups');
        $this->addSql('DROP TABLE products');
        $this->addSql('DROP TABLE product_categories');
    }
}