import { useState } from 'react';
import { Form, InputNumber, Select, Button, Spin, message } from 'antd';
import { MdCalculate } from 'react-icons/md';
import { estimateCost } from '../api';

export default function CostEstimation() {
  const [result, setResult] = useState(null);
  const [loading, setLoading] = useState(false);

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
        <h1>Estimasi Biaya Perbaikan</h1>
      </div>

      <div className="chart-card" style={{ marginBottom: 24 }}>
        <h3>Input Data</h3>
        <Form layout="inline" onFinish={onFinish} style={{ flexWrap: 'wrap', gap: 12 }}>
          <Form.Item name="road_length" label="Panjang (m)" rules={[{ required: true }]}>
            <InputNumber min={1} max={10000} placeholder="0" style={{ width: 120 }} />
          </Form.Item>
          <Form.Item name="road_width" label="Lebar (m)" rules={[{ required: true }]}>
            <InputNumber min={1} max={100} placeholder="0" style={{ width: 120 }} />
          </Form.Item>
          <Form.Item name="damage_type" label="Jenis Kerusakan" rules={[{ required: true }]}>
            <Select
              style={{ width: 160 }}
              options={[
                { value: 'retak', label: 'Retak' },
                { value: 'berlubang', label: 'Berlubang' },
                { value: 'amblas', label: 'Amblas' },
              ]}
            />
          </Form.Item>
          <Form.Item>
            <Button type="primary" htmlType="submit" loading={loading} icon={<MdCalculate />}>
              Hitung Estimasi
            </Button>
          </Form.Item>
        </Form>
      </div>

      {loading && <Spin size="large" style={{ display: 'block', margin: '40px auto' }} />}

      {result && (
        <>
          <div className="cost-comparison">
            {/* Aspal Card */}
            <div className="cost-card">
              <div style={{ display: 'flex', alignItems: 'center', gap: 8, marginBottom: 12 }}>
                <div style={{ width: 40, height: 40, borderRadius: 8, background: '#E8F4F8', display: 'flex', alignItems: 'center', justifyContent: 'center', fontSize: 20 }}>
                  🛣️
                </div>
                <h3 style={{ margin: 0 }}>ASPAL</h3>
              </div>
              <div className="price">{result.aspal.formatted_cost}</div>
              <div style={{ marginTop: 12, display: 'flex', flexDirection: 'column', gap: 6, fontSize: 13, color: '#6C757D' }}>
                <div><strong>Durabilitas:</strong> {result.aspal.durability}</div>
                <div><strong>Waktu Perbaikan:</strong> {result.aspal.repair_time}</div>
                <div style={{ marginTop: 8 }}>
                  <strong>Kelebihan:</strong>
                  <ul style={{ paddingLeft: 16, margin: '4px 0' }}>
                    {result.aspal.pros.map((p, i) => <li key={i}>{p}</li>)}
                  </ul>
                </div>
              </div>
            </div>

            {/* Beton Card */}
            <div className="cost-card">
              <div style={{ display: 'flex', alignItems: 'center', gap: 8, marginBottom: 12 }}>
                <div style={{ width: 40, height: 40, borderRadius: 8, background: '#FEF3E2', display: 'flex', alignItems: 'center', justifyContent: 'center', fontSize: 20 }}>
                  🏗️
                </div>
                <h3 style={{ margin: 0 }}>BETON / COR</h3>
              </div>
              <div className="price">{result.beton.formatted_cost}</div>
              <div style={{ marginTop: 12, display: 'flex', flexDirection: 'column', gap: 6, fontSize: 13, color: '#6C757D' }}>
                <div><strong>Durabilitas:</strong> {result.beton.durability}</div>
                <div><strong>Waktu Perbaikan:</strong> {result.beton.repair_time}</div>
                <div style={{ marginTop: 8 }}>
                  <strong>Kelebihan:</strong>
                  <ul style={{ paddingLeft: 16, margin: '4px 0' }}>
                    {result.beton.pros.map((p, i) => <li key={i}>{p}</li>)}
                  </ul>
                </div>
              </div>
            </div>
          </div>

          {/* Cost Breakdown Table */}
          <div className="chart-grid">
            <div className="chart-card">
              <h3>Breakdown Biaya — Aspal</h3>
              <table style={{ width: '100%', borderCollapse: 'collapse' }}>
                <thead>
                  <tr style={{ borderBottom: '2px solid #f0f0f0' }}>
                    <th style={th}>Material</th>
                    <th style={th}>Volume</th>
                    <th style={th}>Harga Satuan</th>
                    <th style={th}>Total</th>
                  </tr>
                </thead>
                <tbody>
                  {result.aspal.breakdown.map((b, i) => (
                    <tr key={i} style={{ borderBottom: '1px solid #f5f5f5' }}>
                      <td style={td}>{b.material}</td>
                      <td style={td}>{b.volume}</td>
                      <td style={td}>{b.unit_price}</td>
                      <td style={td}><strong>{b.total}</strong></td>
                    </tr>
                  ))}
                </tbody>
              </table>
            </div>

            <div className="chart-card">
              <h3>Breakdown Biaya — Beton/Cor</h3>
              <table style={{ width: '100%', borderCollapse: 'collapse' }}>
                <thead>
                  <tr style={{ borderBottom: '2px solid #f0f0f0' }}>
                    <th style={th}>Material</th>
                    <th style={th}>Volume</th>
                    <th style={th}>Harga Satuan</th>
                    <th style={th}>Total</th>
                  </tr>
                </thead>
                <tbody>
                  {result.beton.breakdown.map((b, i) => (
                    <tr key={i} style={{ borderBottom: '1px solid #f5f5f5' }}>
                      <td style={td}>{b.material}</td>
                      <td style={td}>{b.volume}</td>
                      <td style={td}>{b.unit_price}</td>
                      <td style={td}><strong>{b.total}</strong></td>
                    </tr>
                  ))}
                </tbody>
              </table>
            </div>
          </div>

          <div style={{ textAlign: 'center', marginTop: 8, color: '#999', fontSize: 12 }}>
            Luas area: {result.input.area} m² | Jenis kerusakan: {result.input.damage_type}
          </div>
        </>
      )}
    </div>
  );
}

const th = { textAlign: 'left', padding: '8px 12px', fontSize: 13, color: '#6C757D', fontWeight: 600 };
const td = { padding: '8px 12px', fontSize: 13 };
