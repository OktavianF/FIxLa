import axios from 'axios';

const api = axios.create({
  baseURL: '/api/v1',
  headers: {
    'Content-Type': 'application/json',
    Accept: 'application/json',
  },
});

api.interceptors.request.use((config) => {
  const token = localStorage.getItem('fixla_token');
  if (token) {
    config.headers.Authorization = `Bearer ${token}`;
  }
  return config;
});

api.interceptors.response.use(
  (response) => response,
  (error) => {
    if (error.response?.status === 401) {
      localStorage.removeItem('fixla_token');
      localStorage.removeItem('fixla_user');
      window.location.href = '/login';
    }
    return Promise.reject(error);
  }
);

// Auth
export const login = (email, password) => api.post('/login', { email, password });
export const logout = () => api.post('/logout');
export const getMe = () => api.get('/me');

// Admin Dashboard
export const getOverview = () => api.get('/admin/dashboard/overview');
export const getReportsByDistrict = () => api.get('/admin/dashboard/reports-by-district');
export const getDamageDistribution = () => api.get('/admin/dashboard/damage-distribution');
export const getPriorityRanking = (limit = 10) => api.get(`/admin/dashboard/priority-ranking?limit=${limit}`);
export const getHeatmapData = () => api.get('/admin/dashboard/heatmap');
export const getMonthlyTrend = () => api.get('/admin/dashboard/monthly-trend');
export const getCostSummary = () => api.get('/admin/dashboard/cost-summary');

// Reports
export const getReports = (params) => api.get('/reports', { params });
export const getReport = (id) => api.get(`/reports/${id}`);
export const getMapReports = (bounds) => api.get('/reports/map', { params: { bounds } });
export const updateReportStatus = (id, data) => api.patch(`/admin/reports/${id}/status`, data);
export const deleteReport = (id) => api.delete(`/admin/reports/${id}`);

// Cost Estimation
export const estimateCost = (data) => api.post('/admin/cost-estimation', data);

// Admin Notifications (system-wide)
export const getAdminNotifications = (params) => api.get('/admin/notifications', { params });
export const getAdminUnreadCount = () => api.get('/admin/notifications/unread-count');
export const markAllAdminNotifsAsRead = () => api.patch('/admin/notifications/read-all');

export default api;
