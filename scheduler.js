import cron from 'node-cron';
import axios from 'axios';

// Konfigurasi API endpoint
const API_BASE_URL = process.env.APP_URL || 'http://localhost:8000';

// Logger helper
const logger = {
  info: (message) => console.log(`[${new Date().toISOString()}] INFO: ${message}`),
  error: (message, error) => console.error(`[${new Date().toISOString()}] ERROR: ${message}`, error),
  warn: (message) => console.warn(`[${new Date().toISOString()}] WARN: ${message}`)
};

// Task: Mark absent students every day at 18:00 AM
const markAbsentTask = cron.schedule('00 18 * * *', async () => {
  try {
    logger.info('Running: Mark Absent Students');
    const response = await axios.post(`${API_BASE_URL}/scheduler/mark-absent`, {
      timestamp: new Date()
    });
    logger.info(`Mark Absent completed: ${response.status}`);
  } catch (error) {
    logger.error('Mark Absent failed', error.message);
  }
});

// Task: Check and mark bolos (students who didn't checkout) at 18:00 AM
const checkBolosTask = cron.schedule('00 18 * * *', async () => {
  try {
    logger.info('Running: Check Bolos');
    const response = await axios.post(`${API_BASE_URL}/scheduler/check-bolos`, {
      timestamp: new Date()
    });
    logger.info(`Check Bolos completed: ${response.status}`);
  } catch (error) {
    logger.error('Check Bolos failed', error.message);
  }
});

// Graceful shutdown
process.on('SIGINT', () => {
  logger.info('Stopping scheduler...');
  markAbsentTask.stop();
  checkBolosTask.stop();
  process.exit(0);
});

logger.info('Scheduler started. Running tasks:');
logger.info('- Mark Absent: Every day at 18:00 (6:00 PM)');
logger.info('- Check Bolos: Every day at 18:00 (6:00 PM)');
