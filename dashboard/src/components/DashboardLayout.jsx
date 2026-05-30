import { useState, useRef, useEffect, useCallback } from 'react';
import { Outlet, NavLink, useNavigate } from 'react-router-dom';
import { motion, AnimatePresence } from 'framer-motion';
import { useQuery, useMutation, useQueryClient } from '@tanstack/react-query';
import {
  MdDashboard,
  MdReport,
  MdMap,
  MdPriorityHigh,
  MdAttachMoney,
  MdBarChart,
  MdLogout,
  MdSearch,
  MdNotifications,
  MdDoneAll,
  MdCircle,
  MdInbox,
  MdClose,
} from 'react-icons/md';
import { getAdminNotifications, getAdminUnreadCount, markAllAdminNotifsAsRead } from '../api';

const menuItems = [
  { path: '/', icon: <MdDashboard />, label: 'Dashboard' },
  { path: '/reports', icon: <MdReport />, label: 'Laporan Masuk' },
  { path: '/map', icon: <MdMap />, label: 'Peta Kerusakan' },
  { path: '/priority', icon: <MdPriorityHigh />, label: 'Prioritas' },
  { path: '/cost-estimation', icon: <MdAttachMoney />, label: 'Estimasi Biaya' },
  { path: '/statistics', icon: <MdBarChart />, label: 'Statistik' },
];

function timeAgo(dateStr) {
  const now = new Date();
  const date = new Date(dateStr);
  const seconds = Math.floor((now - date) / 1000);
  if (seconds < 60) return 'Baru saja';
  const minutes = Math.floor(seconds / 60);
  if (minutes < 60) return `${minutes} menit lalu`;
  const hours = Math.floor(minutes / 60);
  if (hours < 24) return `${hours} jam lalu`;
  const days = Math.floor(hours / 24);
  if (days < 7) return `${days} hari lalu`;
  return date.toLocaleDateString('id-ID');
}

export default function DashboardLayout() {
  const navigate = useNavigate();
  const queryClient = useQueryClient();
  const user = JSON.parse(localStorage.getItem('fixla_user') || '{}');

  // ─── Search State ───
  const [searchQuery, setSearchQuery] = useState('');
  const [searchFocused, setSearchFocused] = useState(false);
  const searchInputRef = useRef(null);

  const handleSearch = useCallback((e) => {
    if (e.key === 'Enter' && searchQuery.trim()) {
      navigate(`/reports?search=${encodeURIComponent(searchQuery.trim())}`);
      searchInputRef.current?.blur();
    }
  }, [searchQuery, navigate]);

  // ─── Notification State ───
  const [notifOpen, setNotifOpen] = useState(false);
  const notifRef = useRef(null);

  const { data: unreadData } = useQuery({
    queryKey: ['admin-unread-count'],
    queryFn: () => getAdminUnreadCount().then((r) => r.data.data.count),
    refetchInterval: 30000,
    staleTime: 10000,
  });

  const { data: notifData, isLoading: notifLoading } = useQuery({
    queryKey: ['admin-notifications'],
    queryFn: () => getAdminNotifications().then((r) => r.data),
    enabled: notifOpen,
    staleTime: 5000,
  });

  const markAllReadMutation = useMutation({
    mutationFn: () => markAllAdminNotifsAsRead(),
    onSuccess: () => {
      queryClient.invalidateQueries(['admin-unread-count']);
      queryClient.invalidateQueries(['admin-notifications']);
    },
  });

  const unreadCount = unreadData ?? 0;
  const notifications = notifData?.data ?? [];

  // Click outside to close notification panel
  useEffect(() => {
    function handleClickOutside(e) {
      if (notifRef.current && !notifRef.current.contains(e.target)) {
        setNotifOpen(false);
      }
    }
    if (notifOpen) {
      document.addEventListener('mousedown', handleClickOutside);
    }
    return () => document.removeEventListener('mousedown', handleClickOutside);
  }, [notifOpen]);

  const handleLogout = () => {
    localStorage.removeItem('fixla_token');
    localStorage.removeItem('fixla_user');
    navigate('/login');
  };

  return (
    <div className="dashboard-layout">
      <aside style={styles.sidebar}>
        {/* Decorative gradient orb */}
        <div style={styles.sidebarOrb} />
        
        <div style={styles.logo}>
          <motion.img 
            src="/logo-light.png" 
            alt="FixLA" 
            style={{ width: '100%', maxWidth: 170, height: 'auto', objectFit: 'contain', objectPosition: 'left center' }} 
            whileHover={{ scale: 1.03 }}
            transition={{ type: 'spring', stiffness: 300 }}
          />
        </div>

        <nav style={styles.nav}>
          <div style={styles.navHeader}>Main Menu</div>
          {menuItems.map((item, i) => (
            <NavLink
              key={item.path}
              to={item.path}
              end={item.path === '/'}
              style={({ isActive }) => ({
                ...styles.navLink,
                background: isActive ? 'linear-gradient(90deg, rgba(99, 102, 241, 0.15), rgba(99, 102, 241, 0.05))' : 'transparent',
                color: isActive ? '#A5B4FC' : '#64748B',
                borderLeft: isActive ? '3px solid #818CF8' : '3px solid transparent',
                fontWeight: isActive ? 700 : 500,
              })}
            >
              <span style={{ fontSize: 22, display: 'flex' }}>{item.icon}</span>
              <span>{item.label}</span>
            </NavLink>
          ))}
        </nav>

        <div style={styles.userInfo}>
          <div style={styles.avatar}>
            {(user.name || 'A').charAt(0)}
          </div>
          <div style={{ flex: 1, overflow: 'hidden' }}>
            <p style={{ color: '#F1F5F9', fontSize: 14, fontWeight: 700, whiteSpace: 'nowrap', textOverflow: 'ellipsis', overflow: 'hidden' }}>{user.name || 'Admin User'}</p>
            <p style={{ color: '#475569', fontSize: 12, whiteSpace: 'nowrap', textOverflow: 'ellipsis', overflow: 'hidden' }}>{user.email || 'admin@fixla.id'}</p>
          </div>
          <motion.button 
            onClick={handleLogout} 
            style={styles.logoutBtn} 
            title="Keluar"
            whileHover={{ scale: 1.1, backgroundColor: 'rgba(239, 68, 68, 0.2)' }}
            whileTap={{ scale: 0.95 }}
          >
            <MdLogout />
          </motion.button>
        </div>
      </aside>

      <main className="main-content">
        <div className="top-header">
          <div>
            <p style={{ fontSize: 14, color: '#94A3B8', fontWeight: 500 }}>
              {new Date().toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' })}
            </p>
          </div>
          <div style={{ display: 'flex', alignItems: 'center', gap: 12 }}>
            {/* ─── Search Bar ─── */}
            <div 
              className="header-search" 
              style={{
                borderColor: searchFocused ? '#818CF8' : undefined,
                boxShadow: searchFocused ? '0 0 0 3px rgba(129, 140, 248, 0.15)' : undefined,
                transition: 'all 0.2s ease',
              }}
            >
              <MdSearch style={{ color: searchFocused ? '#818CF8' : '#94A3B8', fontSize: 20, transition: 'color 0.2s' }} />
              <input
                ref={searchInputRef}
                type="text"
                placeholder="Cari laporan, kecamatan..."
                value={searchQuery}
                onChange={(e) => setSearchQuery(e.target.value)}
                onKeyDown={handleSearch}
                onFocus={() => setSearchFocused(true)}
                onBlur={() => setSearchFocused(false)}
                style={{ border: 'none', outline: 'none', background: 'transparent', fontSize: 14, color: '#0F172A', width: 220, fontFamily: 'inherit' }}
              />
              {searchQuery && (
                <motion.button
                  initial={{ opacity: 0, scale: 0.5 }}
                  animate={{ opacity: 1, scale: 1 }}
                  onClick={() => setSearchQuery('')}
                  style={{ 
                    background: 'none', border: 'none', cursor: 'pointer', color: '#94A3B8', 
                    display: 'flex', alignItems: 'center', padding: 2, borderRadius: 4,
                  }}
                >
                  <MdClose style={{ fontSize: 16 }} />
                </motion.button>
              )}
            </div>

            {/* ─── Notification Bell ─── */}
            <div ref={notifRef} style={{ position: 'relative' }}>
              <motion.div
                className="header-notif"
                onClick={() => setNotifOpen((prev) => !prev)}
                whileHover={{ scale: 1.05 }}
                whileTap={{ scale: 0.95 }}
                style={{ cursor: 'pointer', position: 'relative' }}
              >
                <MdNotifications style={{ fontSize: 22, color: notifOpen ? '#818CF8' : '#64748B', transition: 'color 0.2s' }} />
                {unreadCount > 0 && (
                  <motion.span
                    initial={{ scale: 0 }}
                    animate={{ scale: 1 }}
                    style={{
                      position: 'absolute',
                      top: 6,
                      right: 6,
                      width: unreadCount > 9 ? 20 : 16,
                      height: 16,
                      borderRadius: 8,
                      background: 'linear-gradient(135deg, #EF4444, #DC2626)',
                      color: '#fff',
                      fontSize: 10,
                      fontWeight: 800,
                      display: 'flex',
                      alignItems: 'center',
                      justifyContent: 'center',
                      boxShadow: '0 2px 6px rgba(239, 68, 68, 0.4)',
                      border: '2px solid #fff',
                    }}
                  >
                    {unreadCount > 99 ? '99+' : unreadCount}
                  </motion.span>
                )}
              </motion.div>

              {/* ─── Notification Dropdown ─── */}
              <AnimatePresence>
                {notifOpen && (
                  <motion.div
                    initial={{ opacity: 0, y: -8, scale: 0.96 }}
                    animate={{ opacity: 1, y: 0, scale: 1 }}
                    exit={{ opacity: 0, y: -8, scale: 0.96 }}
                    transition={{ duration: 0.2, ease: [0.4, 0, 0.2, 1] }}
                    style={styles.notifPanel}
                  >
                    {/* Header */}
                    <div style={styles.notifHeader}>
                      <h3 style={{ margin: 0, fontSize: 15, fontWeight: 800, color: '#0F172A' }}>Notifikasi</h3>
                      {unreadCount > 0 && (
                        <motion.button
                          whileHover={{ scale: 1.05 }}
                          whileTap={{ scale: 0.95 }}
                          onClick={() => markAllReadMutation.mutate()}
                          disabled={markAllReadMutation.isPending}
                          style={{
                            display: 'flex', alignItems: 'center', gap: 4,
                            background: 'none', border: 'none', color: '#6366F1',
                            fontSize: 12, fontWeight: 700, cursor: 'pointer',
                            opacity: markAllReadMutation.isPending ? 0.5 : 1,
                          }}
                        >
                          <MdDoneAll style={{ fontSize: 16 }} />
                          Tandai semua dibaca
                        </motion.button>
                      )}
                    </div>

                    {/* Notification List */}
                    <div style={styles.notifList}>
                      {notifLoading ? (
                        <div style={styles.notifEmpty}>
                          <div style={{ width: 24, height: 24, border: '3px solid #E2E8F0', borderTopColor: '#6366F1', borderRadius: '50%', animation: 'spin 0.8s linear infinite' }} />
                          <p style={{ color: '#94A3B8', fontSize: 13 }}>Memuat notifikasi...</p>
                        </div>
                      ) : notifications.length === 0 ? (
                        <div style={styles.notifEmpty}>
                          <MdInbox style={{ fontSize: 40, color: '#CBD5E1' }} />
                          <p style={{ color: '#94A3B8', fontSize: 13, fontWeight: 600 }}>Belum ada notifikasi</p>
                        </div>
                      ) : (
                        notifications.map((notif) => (
                          <motion.div
                            key={notif.id}
                            whileHover={{ backgroundColor: '#F8FAFC' }}
                            style={{
                              ...styles.notifItem,
                              background: notif.is_read ? 'transparent' : 'rgba(99, 102, 241, 0.03)',
                            }}
                            onClick={() => {
                              if (notif.report_id) {
                                setNotifOpen(false);
                                navigate(`/reports`);
                              }
                            }}
                          >
                            <div style={{ flex: 'none', paddingTop: 2 }}>
                              {!notif.is_read ? (
                                <MdCircle style={{ fontSize: 8, color: '#6366F1' }} />
                              ) : (
                                <div style={{ width: 8 }} />
                              )}
                            </div>
                            <div style={{ flex: 1, minWidth: 0 }}>
                              <p style={{ 
                                margin: 0, fontSize: 13, fontWeight: notif.is_read ? 500 : 700, 
                                color: '#1E293B', lineHeight: 1.4,
                                whiteSpace: 'nowrap', overflow: 'hidden', textOverflow: 'ellipsis',
                              }}>
                                {notif.title}
                              </p>
                              <p style={{ 
                                margin: '2px 0 0', fontSize: 12, color: '#64748B', lineHeight: 1.4,
                                whiteSpace: 'nowrap', overflow: 'hidden', textOverflow: 'ellipsis',
                              }}>
                                {notif.message}
                              </p>
                              <p style={{ margin: '4px 0 0', fontSize: 11, color: '#94A3B8', fontWeight: 500 }}>
                                {timeAgo(notif.created_at)}
                              </p>
                            </div>
                          </motion.div>
                        ))
                      )}
                    </div>
                  </motion.div>
                )}
              </AnimatePresence>
            </div>
          </div>
        </div>
        <AnimatePresence mode="wait">
          <motion.div
            key={window.location.pathname}
            initial={{ opacity: 0, y: 12 }}
            animate={{ opacity: 1, y: 0 }}
            exit={{ opacity: 0, y: -12 }}
            transition={{ duration: 0.3, ease: [0.4, 0, 0.2, 1] }}
          >
            <Outlet />
          </motion.div>
        </AnimatePresence>
      </main>
    </div>
  );
}

const styles = {
  sidebar: {
    width: 280,
    background: 'linear-gradient(180deg, #0F172A 0%, #1E293B 100%)',
    position: 'fixed',
    top: 0,
    left: 0,
    bottom: 0,
    display: 'flex',
    flexDirection: 'column',
    zIndex: 100,
    borderRight: '1px solid rgba(255,255,255,0.04)',
    overflow: 'hidden',
  },
  sidebarOrb: {
    position: 'absolute',
    top: -60,
    right: -60,
    width: 200,
    height: 200,
    borderRadius: '50%',
    background: 'radial-gradient(circle, rgba(99, 102, 241, 0.15) 0%, transparent 70%)',
    filter: 'blur(40px)',
    pointerEvents: 'none',
  },
  logo: {
    padding: '32px 28px 28px',
    display: 'flex',
    alignItems: 'center',
    gap: 14,
    position: 'relative',
  },
  nav: {
    flex: 1,
    padding: '12px 0',
    display: 'flex',
    flexDirection: 'column',
    gap: 2,
  },
  navHeader: {
    padding: '0 28px',
    fontSize: 11,
    fontWeight: 800,
    color: '#475569',
    textTransform: 'uppercase',
    letterSpacing: '1.5px',
    marginBottom: 16,
  },
  navLink: {
    display: 'flex',
    alignItems: 'center',
    gap: 14,
    padding: '13px 28px',
    textDecoration: 'none',
    fontSize: 14,
    transition: 'all 0.25s cubic-bezier(0.4, 0, 0.2, 1)',
    letterSpacing: '-0.1px',
  },
  userInfo: {
    display: 'flex',
    alignItems: 'center',
    gap: 12,
    padding: '24px 28px',
    background: 'rgba(255, 255, 255, 0.02)',
    borderTop: '1px solid rgba(255, 255, 255, 0.04)',
    backdropFilter: 'blur(10px)',
  },
  avatar: {
    width: 42,
    height: 42,
    borderRadius: 14,
    background: 'linear-gradient(135deg, #6366F1 0%, #4338CA 100%)',
    display: 'flex',
    alignItems: 'center',
    justifyContent: 'center',
    color: 'white',
    fontSize: 16,
    fontWeight: 800,
    boxShadow: '0 4px 12px rgba(99, 102, 241, 0.3)',
  },
  logoutBtn: {
    background: 'rgba(239, 68, 68, 0.08)',
    border: 'none',
    color: '#F87171',
    fontSize: 20,
    cursor: 'pointer',
    width: 38,
    height: 38,
    borderRadius: 12,
    display: 'flex',
    alignItems: 'center',
    justifyContent: 'center',
    transition: 'all 0.2s',
  },
  // ─── Notification Panel ───
  notifPanel: {
    position: 'absolute',
    top: 'calc(100% + 12px)',
    right: 0,
    width: 380,
    maxHeight: 460,
    background: '#fff',
    borderRadius: 20,
    boxShadow: '0 20px 60px rgba(0, 0, 0, 0.12), 0 4px 16px rgba(0, 0, 0, 0.06)',
    border: '1px solid #F1F5F9',
    overflow: 'hidden',
    zIndex: 1000,
    display: 'flex',
    flexDirection: 'column',
  },
  notifHeader: {
    display: 'flex',
    alignItems: 'center',
    justifyContent: 'space-between',
    padding: '18px 20px 14px',
    borderBottom: '1px solid #F1F5F9',
  },
  notifList: {
    flex: 1,
    overflowY: 'auto',
    maxHeight: 380,
  },
  notifItem: {
    display: 'flex',
    alignItems: 'flex-start',
    gap: 10,
    padding: '14px 20px',
    cursor: 'pointer',
    borderBottom: '1px solid #F8FAFC',
    transition: 'background 0.15s',
  },
  notifEmpty: {
    display: 'flex',
    flexDirection: 'column',
    alignItems: 'center',
    justifyContent: 'center',
    gap: 10,
    padding: '48px 20px',
  },
};
