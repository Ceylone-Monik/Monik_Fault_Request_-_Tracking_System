-- Run this in phpMyAdmin or MySQL to add timestamp tracking columns
-- These two columns track when work started and when fault was resolved

ALTER TABLE faults
  ADD COLUMN started_at  DATETIME NULL DEFAULT NULL AFTER technician_notes,
  ADD COLUMN resolved_at DATETIME NULL DEFAULT NULL AFTER started_at;
