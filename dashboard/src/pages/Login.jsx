import { useState } from 'react';
import { useNavigate } from 'react-router-dom';
import { motion } from 'framer-motion';
import { MdEmail, MdLock, MdVisibility, MdVisibilityOff } from 'react-icons/md';
import { login } from '../api';

export default function Login() {
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');
  const [showPw, setShowPw] = useState(false);
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState('');
  const navigate = useNavigate();

  const handleSubmit = async (e) => {
    e.preventDefault();
    setLoading(true);
    setError('');
    try {
      const res = await login(email, password);
      localStorage.setItem('fixla_token', res.data.data.token);
      localStorage.setItem('fixla_user', JSON.stringify(res.data.data.user));
      navigate('/');
    } catch (err) {
      setError(err.response?.data?.message || 'Login gagal');
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="login-container">
      {/* Animated floating particles */}
      {[...Array(6)].map((_, i) => (
        <motion.div
          key={i}
          style={{
            position: 'absolute',
            width: 6 + i * 4,
            height: 6 + i * 4,
            borderRadius: '50%',
            background: `rgba(99, 102, 241, ${0.1 + i * 0.04})`,
            top: `${15 + i * 14}%`,
            left: `${10 + i * 15}%`,
            zIndex: 0,
          }}
          animate={{
            y: [0, -30, 0],
            x: [0, 15, 0],
            opacity: [0.3, 0.8, 0.3],
          }}
          transition={{
            duration: 4 + i,
            repeat: Infinity,
            ease: 'easeInOut',
            delay: i * 0.5,
          }}
        />
      ))}

      <motion.div 
        className="login-card"
        initial={{ opacity: 0, y: 30, scale: 0.95 }}
        animate={{ opacity: 1, y: 0, scale: 1 }}
        transition={{ duration: 0.6, ease: [0.16, 1, 0.3, 1] }}
      >
        <div className="logo">
          <motion.img 
            src="/logo-dark.png" 
            alt="FixLA" 
            style={{ height: 60, maxWidth: '100%', objectFit: 'contain', marginBottom: 12 }} 
            initial={{ y: -10, opacity: 0 }}
            animate={{ y: 0, opacity: 1 }}
            transition={{ delay: 0.3, type: 'spring', stiffness: 200 }}
          />
          <p style={{ marginTop: 4 }}>Government Portal</p>
        </div>

        <form onSubmit={handleSubmit}>
          <div style={{ marginBottom: 20 }}>
            <div style={inputWrapper}>
              <MdEmail style={{ color: '#94A3B8', fontSize: 20 }} />
              <input
                type="email"
                placeholder="Email Address"
                value={email}
                onChange={(e) => setEmail(e.target.value)}
                style={inputStyle}
                required
              />
            </div>
          </div>

          <div style={{ marginBottom: 24 }}>
            <div style={inputWrapper}>
              <MdLock style={{ color: '#94A3B8', fontSize: 20 }} />
              <input
                type={showPw ? 'text' : 'password'}
                placeholder="Password"
                value={password}
                onChange={(e) => setPassword(e.target.value)}
                style={inputStyle}
                required
              />
              <motion.button 
                type="button" 
                onClick={() => setShowPw(!showPw)} 
                style={{ background: 'none', border: 'none', cursor: 'pointer', color: '#94A3B8', display: 'flex', padding: 4 }}
                whileTap={{ scale: 0.9 }}
              >
                {showPw ? <MdVisibility fontSize={20} /> : <MdVisibilityOff fontSize={20} />}
              </motion.button>
            </div>
          </div>

          {error && (
            <motion.p 
              initial={{ opacity: 0, y: -5 }}
              animate={{ opacity: 1, y: 0 }}
              style={{ color: '#EF4444', fontSize: 14, marginBottom: 16, textAlign: 'center', fontWeight: 600, padding: '10px 16px', background: '#FEF2F2', borderRadius: 12 }}
            >
              {error}
            </motion.p>
          )}

          <motion.button
            type="submit"
            disabled={loading}
            style={{
              width: '100%',
              padding: '16px',
              background: 'linear-gradient(135deg, #6366F1, #4338CA)',
              color: 'white',
              border: 'none',
              borderRadius: 16,
              fontSize: 16,
              fontWeight: 700,
              cursor: loading ? 'not-allowed' : 'pointer',
              opacity: loading ? 0.7 : 1,
              fontFamily: 'inherit',
              boxShadow: '0 4px 20px rgba(99, 102, 241, 0.35)',
              transition: 'all 0.3s ease',
              letterSpacing: '-0.2px',
            }}
            whileHover={{ scale: 1.02, boxShadow: '0 8px 30px rgba(99, 102, 241, 0.45)' }}
            whileTap={{ scale: 0.98 }}
          >
            {loading ? 'Signing in...' : 'Secure Login'}
          </motion.button>
        </form>

        <p style={{ textAlign: 'center', fontSize: 13, color: '#94A3B8', marginTop: 24, fontWeight: 500 }}>
          Demo: admin@fixla.id / admin123
        </p>
      </motion.div>
    </div>
  );
}

const inputWrapper = {
  display: 'flex',
  alignItems: 'center',
  gap: 12,
  padding: '14px 18px',
  background: '#F8FAFC',
  borderRadius: 16,
  border: '1.5px solid #E2E8F0',
  transition: 'all 0.3s ease',
};

const inputStyle = {
  flex: 1,
  border: 'none',
  outline: 'none',
  background: 'transparent',
  fontSize: 15,
  fontFamily: 'inherit',
  color: '#0F172A',
  fontWeight: 500,
};
