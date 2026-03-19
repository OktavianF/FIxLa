import { useState } from 'react';
import { Outlet, NavLink, useNavigate } from 'react-router-dom';
import {
  MdDashboard,
  MdReport,
  MdMap,
  MdPriorityHigh,
  MdAttachMoney,
  MdBarChart,
  MdLogout,
  MdSettings,
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
        <div style={styles.logo}>
          <div style={styles.logoIcon}>FX</div>
          <div>
            <h2 style={{ color: '#FFFFFF', fontSize: 24, fontWeight: 800, letterSpacing: '-0.5px' }}>FixLA</h2>
            <p style={{ color: '#94A3B8', fontSize: 13, fontWeight: 500, marginTop: 2 }}>Govt. Portal</p>
          </div>
        </div>

        <nav style={styles.nav}>
          <div style={styles.navHeader}>Main Menu</div>
          {menuItems.map((item) => (
            <NavLink
              key={item.path}
              to={item.path}
              end={item.path === '/'}
              style={({ isActive }) => ({
                ...styles.navLink,
                background: isActive ? 'rgba(79, 70, 229, 0.15)' : 'transparent',
                color: isActive ? '#818CF8' : '#94A3B8',
                borderLeft: isActive ? '4px solid #818CF8' : '4px solid transparent',
              })}
            >
              <span style={{ fontSize: 22 }}>{item.icon}</span>
              <span>{item.label}</span>
            </NavLink>
          ))}
        </nav>

        <div style={styles.userInfo}>
          <div style={styles.avatar}>
            {(user.name || 'A').charAt(0)}
          </div>
          <div style={{ flex: 1, overflow: 'hidden' }}>
            <p style={{ color: '#F1F5F9', fontSize: 14, fontWeight: 600, whiteSpace: 'nowrap', textOverflow: 'ellipsis', overflow: 'hidden' }}>{user.name || 'Admin User'}</p>
            <p style={{ color: '#64748B', fontSize: 12, whiteSpace: 'nowrap', textOverflow: 'ellipsis', overflow: 'hidden' }}>{user.email || 'admin@fixla.id'}</p>
          </div>
          <button onClick={handleLogout} style={styles.logoutBtn} title="Keluar">
            <MdLogout />
          </button>
        </div>
      </aside>

      <main className="main-content">
        <Outlet />
      </main>
    </div>
  );
}

const styles = {
  sidebar: {
    width: 260,
    background: '#0F172A',
    position: 'fixed',
    top: 0,
    left: 0,
    bottom: 0,
    display: 'flex',
    flexDirection: 'column',
    zIndex: 100,
    borderRight: '1px solid rgba(255,255,255,0.05)',
  },
  logo: {
    padding: '32px 24px 24px',
    display: 'flex',
    alignItems: 'center',
    gap: 16,
  },
  logoIcon: {
    width: 48,
    height: 48,
    borderRadius: 16,
    background: 'linear-gradient(135deg, #4F46E5 0%, #312E81 100%)',
    color: '#FFF',
    display: 'flex',
    alignItems: 'center',
    justifyContent: 'center',
    fontWeight: 800,
    fontSize: 20,
    boxShadow: '0 8px 16px rgba(79, 70, 229, 0.4)',
  },
  nav: {
    flex: 1,
    padding: '16px 0',
    display: 'flex',
    flexDirection: 'column',
    gap: 4,
  },
  navHeader: {
    padding: '0 28px',
    fontSize: 12,
    fontWeight: 700,
    color: '#475569',
    textTransform: 'uppercase',
    letterSpacing: '1px',
    marginBottom: 12,
  },
  navLink: {
    display: 'flex',
    alignItems: 'center',
    gap: 14,
    padding: '14px 28px',
    textDecoration: 'none',
    fontSize: 15,
    fontWeight: 600,
    transition: 'all 0.2s ease',
  },
  userInfo: {
    display: 'flex',
    alignItems: 'center',
    gap: 12,
    padding: '24px',
    background: 'rgba(255, 255, 255, 0.02)',
    borderTop: '1px solid rgba(255, 255, 255, 0.05)',
  },
  avatar: {
    width: 40,
    height: 40,
    borderRadius: 12,
    background: '#334155',
    display: 'flex',
    alignItems: 'center',
    justifyContent: 'center',
    color: 'white',
    fontSize: 16,
    fontWeight: 700,
  },
  logoutBtn: {
    background: 'rgba(225, 29, 72, 0.1)',
    border: 'none',
    color: '#FB7185',
    fontSize: 20,
    cursor: 'pointer',
    width: 36,
    height: 36,
    borderRadius: 10,
    display: 'flex',
    alignItems: 'center',
    justifyContent: 'center',
    transition: 'all 0.2s',
  },
};
