import { useQuery } from '@tanstack/react-query';
import { Spin } from 'antd';
import {
  BarChart, Bar, XAxis, YAxis, CartesianGrid, Tooltip, ResponsiveContainer, Legend,
  LineChart, Line, AreaChart, Area,
} from 'recharts';
import { getMonthlyTrend, getReportsByDistrict, getDamageDistribution, getOverview } from '../api';

export default function Statistics() {
  const { data: trend, isLoading: lt } = useQuery({
    queryKey: ['monthly-trend'],
    queryFn: () => getMonthlyTrend().then((r) => r.data.data),
  });
  const { data: districts } = useQuery({
    queryKey: ['districts-stats'],
    queryFn: () => getReportsByDistrict().then((r) => r.data.data),
  });
  const { data: overview } = useQuery({
    queryKey: ['overview-stats'],
    queryFn: () => getOverview().then((r) => r.data.data),
  });

  if (lt) return <Spin size="large" style={{ display: 'block', margin: '100px auto' }} />;

  return (
    <div>
      <div className="page-header">
        <h1>Statistik & Analitik</h1>
      </div>

      <div className="chart-grid">
        <div className="chart-card" style={{ gridColumn: '1 / 3' }}>
          <h3>Trend Laporan Bulanan</h3>
          <ResponsiveContainer width="100%" height={320}>
            <AreaChart data={trend || []}>
              <defs>
                <linearGradient id="totalGrad" x1="0" y1="0" x2="0" y2="1">
                  <stop offset="5%" stopColor="#4F46E5" stopOpacity={0.3} />
                  <stop offset="95%" stopColor="#4F46E5" stopOpacity={0} />
                </linearGradient>
                <linearGradient id="compGrad" x1="0" y1="0" x2="0" y2="1">
                  <stop offset="5%" stopColor="#10B981" stopOpacity={0.3} />
                  <stop offset="95%" stopColor="#10B981" stopOpacity={0} />
                </linearGradient>
              </defs>
              <CartesianGrid strokeDasharray="3 3" stroke="#F1F5F9" />
              <XAxis dataKey="month" fontSize={12} stroke="#64748B" />
              <YAxis fontSize={12} stroke="#64748B" />
              <Tooltip cursor={{fill: '#F8FAFC'}} contentStyle={{ borderRadius: 12, border: 'none', boxShadow: '0 4px 20px rgba(0,0,0,0.08)' }} />
              <Legend />
              <Area type="monotone" dataKey="total" stroke="#4F46E5" fill="url(#totalGrad)" name="Total Laporan" strokeWidth={3} />
              <Area type="monotone" dataKey="completed" stroke="#10B981" fill="url(#compGrad)" name="Selesai" strokeWidth={3} />
            </AreaChart>
          </ResponsiveContainer>
        </div>

        <div className="chart-card">
          <h3>Laporan per Kecamatan (Perbandingan)</h3>
          <ResponsiveContainer width="100%" height={300}>
            <BarChart data={districts || []} layout="vertical">
              <CartesianGrid strokeDasharray="3 3" stroke="#F1F5F9" />
              <XAxis type="number" fontSize={12} stroke="#64748B" />
              <YAxis type="category" dataKey="district" fontSize={12} width={100} stroke="#64748B" />
              <Tooltip cursor={{fill: '#F8FAFC'}} contentStyle={{ borderRadius: 12, border: 'none', boxShadow: '0 4px 20px rgba(0,0,0,0.08)' }} />
              <Bar dataKey="total" fill="#4F46E5" radius={[0, 6, 6, 0]} barSize={20} />
            </BarChart>
          </ResponsiveContainer>
        </div>

        <div className="chart-card">
          <h3>Ringkasan Kinerja</h3>
          <div style={{ display: 'flex', flexDirection: 'column', gap: 20, padding: '20px 0' }}>
            {[
              { label: 'Total Laporan', value: overview?.total_reports || 0, color: '#4F46E5' },
              { label: 'Rata-rata Respon', value: `${overview?.avg_response_days || 0} hari`, color: '#F59E0B' },
              { label: 'Selesai Bulan Ini', value: overview?.completed_this_month || 0, color: '#10B981' },
              { label: 'Dalam Proses', value: overview?.in_progress || 0, color: '#E11D48' },
            ].map((s, i) => (
              <div key={i} style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', padding: '16px 20px', background: 'rgba(255,255,255,0.6)', borderRadius: 14, borderLeft: `5px solid ${s.color}`, border: '1px solid rgba(0,0,0,0.03)' }}>
                <span style={{ fontSize: 15, color: '#64748B', fontWeight: 500 }}>{s.label}</span>
                <span style={{ fontSize: 24, fontWeight: 800, color: s.color, letterSpacing: '-0.5px' }}>{s.value}</span>
              </div>
            ))}
          </div>
        </div>
      </div>
    </div>
  );
}
