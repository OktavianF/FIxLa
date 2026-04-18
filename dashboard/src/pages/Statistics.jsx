import { useQuery } from '@tanstack/react-query';
import { Spin } from 'antd';
import { motion } from 'framer-motion';
import {
  BarChart, Bar, XAxis, YAxis, CartesianGrid, Tooltip, ResponsiveContainer, Legend,
  AreaChart, Area,
} from 'recharts';
import { getMonthlyTrend, getReportsByDistrict, getOverview } from '../api';

const CustomTooltip = ({ active, payload, label }) => {
  if (!active || !payload?.length) return null;
  return (
    <div style={{
      background: 'rgba(15, 23, 42, 0.95)',
      backdropFilter: 'blur(12px)',
      padding: '12px 18px',
      borderRadius: 14,
      border: '1px solid rgba(255,255,255,0.1)',
      boxShadow: '0 8px 30px rgba(0,0,0,0.3)',
    }}>
      <p style={{ color: '#94A3B8', fontSize: 12, fontWeight: 600, marginBottom: 6 }}>{label}</p>
      {payload.map((p, i) => (
        <p key={i} style={{ color: p.color || p.stroke, fontSize: 14, fontWeight: 700 }}>
          {p.name}: {p.value}
        </p>
      ))}
    </div>
  );
};

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

  const kpiData = [
    { label: 'Total Laporan', value: overview?.total_reports || 0, color: '#6366F1', icon: '📊' },
    { label: 'Rata-rata Respon', value: `${overview?.avg_response_days || 0} hari`, color: '#F59E0B', icon: '⏱️' },
    { label: 'Selesai Bulan Ini', value: overview?.completed_this_month || 0, color: '#10B981', icon: '✅' },
    { label: 'Dalam Proses', value: overview?.in_progress || 0, color: '#EF4444', icon: '🔧' },
  ];

  return (
    <div>
      <div className="page-header">
        <div>
          <h1 style={{ marginBottom: 6 }}>Statistik & Analitik</h1>
          <p style={{ fontSize: 15, color: '#94A3B8', fontWeight: 500 }}>Pantau performa dan tren laporan kerusakan jalan.</p>
        </div>
      </div>

      <div className="chart-grid">
        <motion.div 
          className="chart-card" 
          style={{ gridColumn: '1 / 3' }}
          initial={{ opacity: 0, y: 20 }}
          animate={{ opacity: 1, y: 0 }}
          transition={{ duration: 0.5 }}
        >
          <h3>Trend Laporan Bulanan</h3>
          <ResponsiveContainer width="100%" height={340}>
            <AreaChart data={trend || []}>
              <defs>
                <linearGradient id="totalGrad2" x1="0" y1="0" x2="0" y2="1">
                  <stop offset="0%" stopColor="#6366F1" stopOpacity={0.25} />
                  <stop offset="100%" stopColor="#6366F1" stopOpacity={0} />
                </linearGradient>
                <linearGradient id="compGrad2" x1="0" y1="0" x2="0" y2="1">
                  <stop offset="0%" stopColor="#10B981" stopOpacity={0.25} />
                  <stop offset="100%" stopColor="#10B981" stopOpacity={0} />
                </linearGradient>
              </defs>
              <CartesianGrid strokeDasharray="3 3" stroke="#F1F5F9" vertical={false} />
              <XAxis dataKey="month" fontSize={12} stroke="#94A3B8" axisLine={false} tickLine={false} />
              <YAxis fontSize={12} stroke="#94A3B8" axisLine={false} tickLine={false} />
              <Tooltip content={<CustomTooltip />} />
              <Legend iconType="circle" iconSize={8} wrapperStyle={{ fontSize: 13, fontWeight: 600, paddingTop: 12 }} />
              <Area type="monotone" dataKey="total" stroke="#6366F1" fill="url(#totalGrad2)" name="Total Laporan" strokeWidth={3} dot={{ r: 4, strokeWidth: 2, fill: '#fff' }} activeDot={{ r: 6, strokeWidth: 2 }} />
              <Area type="monotone" dataKey="completed" stroke="#10B981" fill="url(#compGrad2)" name="Selesai" strokeWidth={3} dot={{ r: 4, strokeWidth: 2, fill: '#fff' }} activeDot={{ r: 6, strokeWidth: 2 }} />
            </AreaChart>
          </ResponsiveContainer>
        </motion.div>

        <motion.div 
          className="chart-card"
          initial={{ opacity: 0, x: -20 }}
          animate={{ opacity: 1, x: 0 }}
          transition={{ delay: 0.2, duration: 0.5 }}
        >
          <h3>Laporan per Kecamatan</h3>
          <ResponsiveContainer width="100%" height={320}>
            <BarChart data={districts || []} layout="vertical" barCategoryGap="15%">
              <defs>
                <linearGradient id="hBarGrad" x1="0" y1="0" x2="1" y2="0">
                  <stop offset="0%" stopColor="#6366F1" stopOpacity={0.8} />
                  <stop offset="100%" stopColor="#A5B4FC" stopOpacity={1} />
                </linearGradient>
              </defs>
              <CartesianGrid strokeDasharray="3 3" stroke="#F1F5F9" horizontal={false} />
              <XAxis type="number" fontSize={12} stroke="#94A3B8" axisLine={false} tickLine={false} />
              <YAxis type="category" dataKey="district" fontSize={12} width={100} stroke="#64748B" axisLine={false} tickLine={false} />
              <Tooltip content={<CustomTooltip />} cursor={{ fill: 'rgba(99, 102, 241, 0.04)' }} />
              <Bar dataKey="total" fill="url(#hBarGrad)" radius={[0, 8, 8, 0]} barSize={18} name="Laporan" />
            </BarChart>
          </ResponsiveContainer>
        </motion.div>

        <motion.div 
          className="chart-card"
          initial={{ opacity: 0, x: 20 }}
          animate={{ opacity: 1, x: 0 }}
          transition={{ delay: 0.3, duration: 0.5 }}
        >
          <h3>Ringkasan Kinerja</h3>
          <div style={{ display: 'flex', flexDirection: 'column', gap: 16, padding: '8px 0' }}>
            {kpiData.map((s, i) => (
              <motion.div 
                key={i} 
                initial={{ opacity: 0, x: -20 }}
                animate={{ opacity: 1, x: 0 }}
                transition={{ delay: 0.4 + i * 0.08 }}
                style={{ 
                  display: 'flex', justifyContent: 'space-between', alignItems: 'center', 
                  padding: '18px 20px', 
                  background: '#FAFBFC',
                  borderRadius: 16, 
                  borderLeft: `4px solid ${s.color}`,
                  transition: 'all 0.3s ease',
                  cursor: 'default',
                }}
                whileHover={{ x: 4, background: '#F8FAFC', boxShadow: '0 4px 20px rgba(0,0,0,0.04)' }}
              >
                <div style={{ display: 'flex', alignItems: 'center', gap: 12 }}>
                  <span style={{ fontSize: 22 }}>{s.icon}</span>
                  <span style={{ fontSize: 14, color: '#64748B', fontWeight: 600 }}>{s.label}</span>
                </div>
                <span style={{ fontSize: 24, fontWeight: 900, color: s.color, letterSpacing: '-0.5px', fontVariantNumeric: 'tabular-nums' }}>{s.value}</span>
              </motion.div>
            ))}
          </div>
        </motion.div>
      </div>
    </div>
  );
}
