import { useState } from 'react';
import { useNavigate } from 'react-router-dom';
import { Form, Input, Button, message } from 'antd';
import { MdEmail, MdLock } from 'react-icons/md';
import { login } from '../api';

export default function Login() {
  const [loading, setLoading] = useState(false);
  const navigate = useNavigate();

  const onFinish = async (values) => {
    setLoading(true);
    try {
      const res = await login(values);
      const { user, token } = res.data.data;

      if (user.role !== 'admin') {
        message.error('Hanya admin yang dapat mengakses dashboard.');
        setLoading(false);
        return;
      }

      localStorage.setItem('fixla_token', token);
      localStorage.setItem('fixla_user', JSON.stringify(user));
      message.success('Login berhasil!');
      navigate('/');
    } catch (err) {
      message.error(err.response?.data?.message || 'Login gagal. Periksa email dan password.');
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="login-container">
      <div className="login-card">
        <div className="logo" style={{ display: 'flex', flexDirection: 'column', alignItems: 'center' }}>
          <div style={{
            width: 64, height: 64, borderRadius: 20,
            background: 'linear-gradient(135deg, #4F46E5 0%, #312E81 100%)',
            color: '#FFF', display: 'flex', alignItems: 'center', justifyContent: 'center',
            fontWeight: 800, fontSize: 28, boxShadow: '0 12px 24px rgba(79, 70, 229, 0.4)',
            marginBottom: 20
          }}>FX</div>
          <h1>FixLA</h1>
          <p>Government Portal</p>
        </div>

        <Form layout="vertical" onFinish={onFinish} size="large" style={{ marginTop: 32 }}>
          <Form.Item
            name="email"
            rules={[
              { required: true, message: 'Masukkan email' },
              { type: 'email', message: 'Format email tidak valid' },
            ]}
          >
            <Input prefix={<MdEmail style={{ color: '#94A3B8' }} />} placeholder="Email admin" style={{ borderRadius: 12, padding: 12 }} />
          </Form.Item>

          <Form.Item
            name="password"
            rules={[{ required: true, message: 'Masukkan password' }]}
          >
            <Input.Password prefix={<MdLock style={{ color: '#94A3B8' }} />} placeholder="Password" style={{ borderRadius: 12, padding: 12 }} />
          </Form.Item>

          <Form.Item>
            <Button
              type="primary"
              htmlType="submit"
              loading={loading}
              block
              style={{ height: 50, fontWeight: 700, borderRadius: 12, fontSize: 16 }}
            >
              Secure Login
            </Button>
          </Form.Item>
        </Form>

        <p style={{ textAlign: 'center', color: '#94A3B8', fontSize: 13, marginTop: 16 }}>
          Demo: admin@fixla.id / admin123
        </p>
      </div>
    </div>
  );
}
