import { useState } from 'react';
import { useQuery } from '@tanstack/react-query';
import { Form, InputNumber, Select, Button, Spin, message, Table, Tag, Statistic, Row, Col } from 'antd';
import { motion, AnimatePresence } from 'framer-motion';
import { MdCalculate, MdAutoAwesome } from 'react-icons/md';
import { estimateCost, getCostSummary } from '../api';

export default function CostEstimation() {
  const [result, setResult] = useState(null);
  const [loading, setLoading] = useState(false);

  const { data: summary, isLoading: loadingSummary } = useQuery({
    queryKey: ['cost-summary'],
    queryFn: () => getCostSummary().then(res => res.data.data),
  });

  const onFinish = async (values) => {
    setLoading(true);
    try {
      const res = await estimateCost(values);
      setResult(res.data.data);
    } catch (err) {
      message.error('Gagal menghitung estimasi');
    } finally {
      setLoading(false);
    }
  };

  return (
    <div>
      <div className="page-header">
        <div>
          <h1 style={{ marginBottom: 6 }}>Estimasi Biaya Perbaikan</h1>
          <p style={{ fontSize: 15, color: '#94A3B8', fontWeight: 500 }}>Sistem otomatis menghitung estimasi biaya berdasarkan data laporan masuk.</p>
        </div>
      </div>

      <div style={{ marginBottom: 32 }}>
        <Row gutter={20}>
          <Col span={8}>
            <div className="chart-card" style={{ background: 'linear-gradient(135deg, #6366F1, #4F46E5)', color: '#fff' }}>
              <Statistic 
                title={<span style={{ color: 'rgba(255,255,255,0.7)', fontWeight: 600 }}>Total Estimasi (Aspal)</span>}
                value={summary?.total_asphalt || 0}
                precision={0}
                prefix="Rp"
                valueStyle={{ color: '#fff', fontWeight: 900, fontSize: 24 }}
              />
            </div>
          </Col>
          <Col span={8}>
            <div className="chart-card" style={{ background: 'linear-gradient(135deg, #F59E0B, #D97706)', color: '#fff' }}>
              <Statistic 
                title={<span style={{ color: 'rgba(255,255,255,0.7)', fontWeight: 600 }}>Total Estimasi (Beton)</span>}
                value={summary?.total_concrete || 0}
                precision={0}
                prefix="Rp"
                valueStyle={{ color: '#fff', fontWeight: 900, fontSize: 24 }}
              />
            </div>
          </Col>
          <Col span={8}>
            <div className="chart-card" style={{ background: '#fff' }}>
              <Statistic 
                title="Jumlah Laporan Terhitung"
                value={summary?.count || 0}
                prefix={<MdAutoAwesome style={{ color: '#6366F1' }} />}
                valueStyle={{ color: '#0F172A', fontWeight: 900 }}
              />
            </div>
          </Col>
        </Row>
      </div>

      <motion.div 
        className="chart-card" 
        style={{ marginBottom: 32, padding: 4 }}
        initial={{ opacity: 0, y: 16 }}
        animate={{ opacity: 1, y: 0 }}
      >
        <h3 style={{ padding: '16px 20px 8px' }}>Rangkuman Biaya Per Laporan</h3>
        <Table 
          dataSource={summary?.reports || []}
          loading={loadingSummary}
          rowKey="id"
          pagination={{ pageSize: 5 }}
          size="middle"
          columns={[
            { title: 'Alamat', dataIndex: 'address', ellipsis: true },
            { title: 'Dimensi', render: (_, r) => `${r.road_length}m × ${r.road_width}m` },
            { title: 'Kerusakan', dataIndex: 'damage_level', render: (v) => <Tag color={v === 'berat' ? 'red' : v === 'sedang' ? 'orange' : 'green'}>{v.toUpperCase()}</Tag> },
            { title: 'Estimasi Aspal', dataIndex: 'estimated_cost_asphalt', render: (v) => <strong style={{ color: '#6366F1' }}>Rp {new Intl.NumberFormat('id-ID').format(v)}</strong> },
            { title: 'Estimasi Beton', dataIndex: 'estimated_cost_concrete', render: (v) => <strong style={{ color: '#D97706' }}>Rp {new Intl.NumberFormat('id-ID').format(v)}</strong> },
          ]}
        />
      </motion.div>

      <div style={{ height: 1, background: '#F1F5F9', marginBottom: 32 }} />

      <h3>Kalkulator Estimasi Manual</h3>
      <motion.div 
        className="chart-card" 
        style={{ marginBottom: 28 }}
        initial={{ opacity: 0, y: 16 }}
        animate={{ opacity: 1, y: 0 }}
        transition={{ duration: 0.4 }}
      >
        <h3>Input Data</h3>
        <Form layout="inline" onFinish={onFinish} style={{ flexWrap: 'wrap', gap: 14 }}>
          <Form.Item name="road_length" label="Panjang (m)" rules={[{ required: true }]}>
            <InputNumber min={1} max={10000} placeholder="0" style={{ width: 130 }} />
          </Form.Item>
          <Form.Item name="road_width" label="Lebar (m)" rules={[{ required: true }]}>
            <InputNumber min={1} max={100} placeholder="0" style={{ width: 130 }} />
          </Form.Item>
          <Form.Item name="damage_type" label="Jenis Kerusakan" rules={[{ required: true }]}>
            <Select
              style={{ width: 170 }}
              options={[
                { value: 'retak', label: 'Retak' },
                { value: 'berlubang', label: 'Berlubang' },
                { value: 'amblas', label: 'Amblas' },
              ]}
            />
          </Form.Item>
          <Form.Item>
            <Button type="primary" htmlType="submit" loading={loading} icon={<MdCalculate />} style={{ height: 40, borderRadius: 14, fontWeight: 700 }}>
              Hitung Estimasi
            </Button>
          </Form.Item>
        </Form>
      </motion.div>

      {loading && <Spin size="large" style={{ display: 'block', margin: '40px auto' }} />}

      <AnimatePresence>
        {result && (
          <motion.div 
            initial={{ opacity: 0, y: 30 }}
            animate={{ opacity: 1, y: 0 }}
            exit={{ opacity: 0, y: -20 }}
            transition={{ duration: 0.5, ease: [0.16, 1, 0.3, 1] }}
          >
            <div className="cost-comparison">
              {/* Aspal Card */}
              <motion.div 
                className="cost-card"
                whileHover={{ y: -6, scale: 1.01 }}
                transition={{ type: 'spring', stiffness: 300, damping: 20 }}
              >
                <div style={{ display: 'flex', alignItems: 'center', gap: 12, marginBottom: 20 }}>
                  <div style={{ width: 48, height: 48, borderRadius: 14, background: 'linear-gradient(135deg, #E0F2FE, #BAE6FD)', display: 'flex', alignItems: 'center', justifyContent: 'center', fontSize: 24 }}>
                    🛣️
                  </div>
                  <div>
                    <h3 style={{ margin: 0, fontSize: 18 }}>ASPAL</h3>
                    <p style={{ fontSize: 12, color: '#94A3B8', fontWeight: 500, margin: 0 }}>Flexible Pavement</p>
                  </div>
                </div>
                <div className="price">{result.aspal.formatted_cost}</div>
                <div style={{ marginTop: 20, display: 'flex', flexDirection: 'column', gap: 10, fontSize: 14, color: '#475569' }}>
                  <div style={{ display: 'flex', justifyContent: 'space-between', padding: '10px 14px', background: '#F8FAFC', borderRadius: 12 }}>
                    <span style={{ fontWeight: 600, color: '#94A3B8' }}>Durabilitas</span>
                    <span style={{ fontWeight: 700 }}>{result.aspal.durability}</span>
                  </div>
                  <div style={{ display: 'flex', justifyContent: 'space-between', padding: '10px 14px', background: '#F8FAFC', borderRadius: 12 }}>
                    <span style={{ fontWeight: 600, color: '#94A3B8' }}>Waktu Perbaikan</span>
                    <span style={{ fontWeight: 700 }}>{result.aspal.repair_time}</span>
                  </div>
                  <div style={{ marginTop: 8 }}>
                    <p style={{ fontWeight: 700, fontSize: 12, color: '#94A3B8', textTransform: 'uppercase', letterSpacing: '0.5px', marginBottom: 8 }}>Kelebihan</p>
                    <div style={{ display: 'flex', flexDirection: 'column', gap: 6 }}>
                      {result.aspal.pros.map((p, i) => (
                        <div key={i} style={{ display: 'flex', alignItems: 'center', gap: 8, fontSize: 13 }}>
                          <span style={{ color: '#10B981', fontSize: 14 }}>✓</span>
                          {p}
                        </div>
                      ))}
                    </div>
                  </div>
                </div>
              </motion.div>

              {/* Beton Card */}
              <motion.div 
                className="cost-card"
                whileHover={{ y: -6, scale: 1.01 }}
                transition={{ type: 'spring', stiffness: 300, damping: 20 }}
              >
                <div style={{ display: 'flex', alignItems: 'center', gap: 12, marginBottom: 20 }}>
                  <div style={{ width: 48, height: 48, borderRadius: 14, background: 'linear-gradient(135deg, #FEF3C7, #FDE68A)', display: 'flex', alignItems: 'center', justifyContent: 'center', fontSize: 24 }}>
                    🏗️
                  </div>
                  <div>
                    <h3 style={{ margin: 0, fontSize: 18 }}>BETON / COR</h3>
                    <p style={{ fontSize: 12, color: '#94A3B8', fontWeight: 500, margin: 0 }}>Rigid Pavement</p>
                  </div>
                </div>
                <div className="price">{result.beton.formatted_cost}</div>
                <div style={{ marginTop: 20, display: 'flex', flexDirection: 'column', gap: 10, fontSize: 14, color: '#475569' }}>
                  <div style={{ display: 'flex', justifyContent: 'space-between', padding: '10px 14px', background: '#F8FAFC', borderRadius: 12 }}>
                    <span style={{ fontWeight: 600, color: '#94A3B8' }}>Durabilitas</span>
                    <span style={{ fontWeight: 700 }}>{result.beton.durability}</span>
                  </div>
                  <div style={{ display: 'flex', justifyContent: 'space-between', padding: '10px 14px', background: '#F8FAFC', borderRadius: 12 }}>
                    <span style={{ fontWeight: 600, color: '#94A3B8' }}>Waktu Perbaikan</span>
                    <span style={{ fontWeight: 700 }}>{result.beton.repair_time}</span>
                  </div>
                  <div style={{ marginTop: 8 }}>
                    <p style={{ fontWeight: 700, fontSize: 12, color: '#94A3B8', textTransform: 'uppercase', letterSpacing: '0.5px', marginBottom: 8 }}>Kelebihan</p>
                    <div style={{ display: 'flex', flexDirection: 'column', gap: 6 }}>
                      {result.beton.pros.map((p, i) => (
                        <div key={i} style={{ display: 'flex', alignItems: 'center', gap: 8, fontSize: 13 }}>
                          <span style={{ color: '#10B981', fontSize: 14 }}>✓</span>
                          {p}
                        </div>
                      ))}
                    </div>
                  </div>
                </div>
              </motion.div>
            </div>

            {/* Cost Breakdown Table */}
            <div className="chart-grid">
              <motion.div 
                className="chart-card"
                initial={{ opacity: 0, x: -20 }}
                animate={{ opacity: 1, x: 0 }}
                transition={{ delay: 0.2 }}
              >
                <h3>Breakdown Biaya — Aspal</h3>
                <table style={{ width: '100%', borderCollapse: 'collapse' }}>
                  <thead>
                    <tr>
                      <th style={thStyle}>Material</th>
                      <th style={thStyle}>Volume</th>
                      <th style={thStyle}>Harga Satuan</th>
                      <th style={thStyle}>Total</th>
                    </tr>
                  </thead>
                  <tbody>
                    {result.aspal.breakdown.map((b, i) => (
                      <tr key={i} style={{ borderBottom: '1px solid #F1F5F9' }}>
                        <td style={tdStyle}>{b.material}</td>
                        <td style={tdStyle}>{b.volume}</td>
                        <td style={tdStyle}>{b.unit_price}</td>
                        <td style={{ ...tdStyle, fontWeight: 700, color: '#6366F1' }}>{b.total}</td>
                      </tr>
                    ))}
                  </tbody>
                </table>
              </motion.div>

              <motion.div 
                className="chart-card"
                initial={{ opacity: 0, x: 20 }}
                animate={{ opacity: 1, x: 0 }}
                transition={{ delay: 0.3 }}
              >
                <h3>Breakdown Biaya — Beton/Cor</h3>
                <table style={{ width: '100%', borderCollapse: 'collapse' }}>
                  <thead>
                    <tr>
                      <th style={thStyle}>Material</th>
                      <th style={thStyle}>Volume</th>
                      <th style={thStyle}>Harga Satuan</th>
                      <th style={thStyle}>Total</th>
                    </tr>
                  </thead>
                  <tbody>
                    {result.beton.breakdown.map((b, i) => (
                      <tr key={i} style={{ borderBottom: '1px solid #F1F5F9' }}>
                        <td style={tdStyle}>{b.material}</td>
                        <td style={tdStyle}>{b.volume}</td>
                        <td style={tdStyle}>{b.unit_price}</td>
                        <td style={{ ...tdStyle, fontWeight: 700, color: '#6366F1' }}>{b.total}</td>
                      </tr>
                    ))}
                  </tbody>
                </table>
              </motion.div>
            </div>

            <motion.p 
              initial={{ opacity: 0 }}
              animate={{ opacity: 1 }}
              transition={{ delay: 0.5 }}
              style={{ textAlign: 'center', marginTop: 12, color: '#94A3B8', fontSize: 13, fontWeight: 500 }}
            >
              Luas area: {result.input.area} m² • Jenis kerusakan: {result.input.damage_type}
            </motion.p>
          </motion.div>
        )}
      </AnimatePresence>
    </div>
  );
}

const thStyle = { textAlign: 'left', padding: '12px 16px', fontSize: 12, color: '#94A3B8', fontWeight: 700, textTransform: 'uppercase', letterSpacing: '0.5px', borderBottom: '2px solid #F1F5F9' };
const tdStyle = { padding: '12px 16px', fontSize: 14 };
