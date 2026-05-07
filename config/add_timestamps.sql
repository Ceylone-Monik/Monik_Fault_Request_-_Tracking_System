-- Run this in phpMyAdmin > fault_management_db > SQL tab

-- Add timestamp columns (skip if already added)
ALTER TABLE faults
  ADD COLUMN IF NOT EXISTS started_at  DATETIME NULL DEFAULT NULL AFTER technician_notes,
  ADD COLUMN IF NOT EXISTS resolved_at DATETIME NULL DEFAULT NULL AFTER started_at;

-- Add branch column
ALTER TABLE faults
  ADD COLUMN IF NOT EXISTS branch VARCHAR(100) NULL DEFAULT NULL AFTER company_name;
