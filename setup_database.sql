-- Create enquiries table to log all form submissions
CREATE TABLE IF NOT EXISTS enquiries (
    id SERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    phone VARCHAR(50),
    message TEXT,
    product_name VARCHAR(255),
    enquiry_type VARCHAR(50) DEFAULT 'general', -- 'general', 'product'
    email_sent BOOLEAN DEFAULT FALSE,
    email_error TEXT,
    ip_address INET,
    user_agent TEXT,
    attachments_count INTEGER DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create index for faster queries
CREATE INDEX IF NOT EXISTS idx_enquiries_email ON enquiries(email);
CREATE INDEX IF NOT EXISTS idx_enquiries_created_at ON enquiries(created_at);
CREATE INDEX IF NOT EXISTS idx_enquiries_email_sent ON enquiries(email_sent);