import { useState } from 'react';
import { Outlet, NavLink, useNavigate } from 'react-router-dom';
import { motion, AnimatePresence } from 'framer-motion';
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
} from 'react-icons/md';

const menuItems = [
  { path: '/', icon: <MdDashboard />, label: 'Dashboard' },
  { path: '/reports', icon: <MdReport />, label: 'Laporan Masuk' },
  { path: '/map', icon: <MdMap />, label: 'Peta Kerusakan' },
  { path: '/priority', icon: <MdPriorityHigh />, label: 'Prioritas' },
  { path: '/cost-estimation', icon: <MdAttachMoney />, label: 'Estimasi Biaya' },
  { path: '/statistics', icon: <MdBarChart />, label: 'Statistik' },
];

export default function DashboardLayout() {
  const navigate = useNavigate();
  const user = JSON.parse(localStorage.getItem('fixla_user') || '{}');

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
            <div className="header-search">
              <MdSearch style={{ color: '#94A3B8', fontSize: 20 }} />
              <input 
                type="text" 
                placeholder="Cari laporan, kecamatan..." 
                style={{ border: 'none', outline: 'none', background: 'transparent', fontSize: 14, color: '#0F172A', width: 220, fontFamily: 'inherit' }} 
              />
            </div>
            <div className="header-notif">
              <MdNotifications style={{ fontSize: 22, color: '#64748B' }} />
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
};
