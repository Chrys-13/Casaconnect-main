-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 14, 2024 at 8:00 AM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

-- Create the database
CREATE DATABASE IF NOT EXISTS `casaconnect`;
USE `casaconnect`;

-- Create the users table
CREATE TABLE `users` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `fname` VARCHAR(100) NOT NULL,
    `lname` VARCHAR(100) NOT NULL,
    `username` VARCHAR(100) NOT NULL UNIQUE,
    `password` VARCHAR(255) NOT NULL,
    `user_role` TINYINT NOT NULL DEFAULT 2 -- Default to regular user
);

-- Create the listings table
CREATE TABLE `listings` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `property_name` VARCHAR(200) NOT NULL,
    `location` VARCHAR(255) NOT NULL,
    `description` TEXT NOT NULL,
    `price` DECIMAL(15, 2) NOT NULL,
    `images` TEXT -- Comma-separated paths for up to 10 images
);

-- Create the booked_consultations table
CREATE TABLE `booked_consultations` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `username` VARCHAR(100) NOT NULL,
    `date` DATE NOT NULL,
    `time` TIME NOT NULL,
    UNIQUE (`username`, `date`), -- Ensure unique date per user
    FOREIGN KEY (`username`) REFERENCES `users`(`username`) ON DELETE CASCADE
);

-- Create the booked_property_valuations table
CREATE TABLE `booked_property_valuations` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `username` VARCHAR(100) NOT NULL,
    `date` DATE NOT NULL,
    `time` TIME NOT NULL,
    UNIQUE (`username`, `date`), -- Ensure unique date per user
    FOREIGN KEY (`username`) REFERENCES `users`(`username`) ON DELETE CASCADE
);

