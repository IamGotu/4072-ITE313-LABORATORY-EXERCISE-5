const express = require('express');
const router = express.Router();
const db = require('../config/db'); // Ensure this path is correct
const bcrypt = require('bcryptjs');

// Middleware to ensure user is authenticated
function isAuthenticated(req, res, next) {
    if (req.isAuthenticated()) {
        return next();
    }
    res.status(401).json({ success: false, message: 'Unauthorized' });
}

// Profile endpoint to get user data
router.get('/', isAuthenticated, (req, res) => {
    const userId = req.user.id; // req.user should be populated if user is authenticated
    db.query('SELECT name, email FROM users WHERE id = ?', [userId], (err, results) => {
        if (err) {
            console.error('Database query error:', err);
            return res.status(500).json({ success: false, message: 'Database error' });
        }
        if (results.length > 0) {
            res.json({ success: true, user: results[0] });
        } else {
            res.status(404).json({ success: false, message: 'User not found' });
        }
    });
});

// Update profile endpoint
router.post('/update', isAuthenticated, (req, res) => {
    console.log(req.body); // Log the request body
    const { name, email, currentPassword, newPassword, confirmPassword } = req.body;

    // Validate required fields
    if (!name || !email) {
        return res.status(400).json({ success: false, message: 'Name and email are required.' });
    }

    // If password is provided, validate it
    if (newPassword) {
        if (newPassword !== confirmPassword) {
            return res.status(400).json({ success: false, message: 'New passwords do not match.' });
        }
    }

    const userId = req.user.id; // Get the authenticated user's ID

    // First, fetch the current password from the database
    db.query('SELECT password FROM users WHERE id = ?', [userId], (err, results) => {
        if (err) {
            console.error('Database query error:', err);
            return res.status(500).json({ success: false, message: 'Database error' });
        }

        if (results.length === 0) {
            return res.status(404).json({ success: false, message: 'User not found.' });
        }

        const storedPasswordHash = results[0].password;

        // Check current password if updating the password
        if (newPassword) {
            bcrypt.compare(currentPassword, storedPasswordHash, (err, isMatch) => {
                if (err) {
                    return res.status(500).json({ success: false, message: 'Error comparing passwords.' });
                }
                if (!isMatch) {
                    return res.status(400).json({ success: false, message: 'Incorrect current password.' });
                }

                // Hash the new password before saving it
                bcrypt.hash(newPassword, 10, (err, hashedPassword) => {
                    if (err) {
                        return res.status(500).json({ success: false, message: 'Error hashing new password.' });
                    }

                    // Update user profile with new name, email, and password
                    db.query('UPDATE users SET name = ?, email = ?, password = ? WHERE id = ?', [name, email, hashedPassword, userId], (err) => {
                        if (err) {
                            console.error('Database update error:', err);
                            return res.status(500).json({ success: false, message: 'Database error while updating profile.' });
                        }
                        res.json({ success: true, message: 'Profile updated successfully!' });
                    });
                });
            });
        } else {
            // If no new password is provided, just update name and email
            db.query('UPDATE users SET name = ?, email = ? WHERE id = ?', [name, email, userId], (err) => {
                if (err) {
                    console.error('Database update error:', err);
                    return res.status(500).json({ success: false, message: 'Database error while updating profile.' });
                }
                res.json({ success: true, message: 'Profile updated successfully!' });
            });
        }
    });
});

module.exports = router;