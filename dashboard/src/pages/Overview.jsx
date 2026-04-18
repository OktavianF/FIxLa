import { useQuery } from '@tanstack/react-query';
import { Spin } from 'antd';
import { motion } from 'framer-motion';
import { MdAssignment, MdBuild, MdCheckCircle } from 'react-icons/md';
import {
  BarChart, Bar, XAxis, YAxis, CartesianGrid, Tooltip, ResponsiveContainer,
  PieChart, Pie, Cell, Legend,
} from 'recharts';
import { getOverview, getReportsByDistrict, getDamageDistribution, getPriorityRanking } from '../api';

const COLORS = ['#10B981', '#F59E0B', '#EF4444'];
const STATUS_LABELS = {
  submitted: 'Submitted',
  verified: 'Verified',
  scheduled: 'Scheduled',
  under_repair: 'Under Repair',
  completed: 'Completed',
};

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
        <p key={i} style={{ color: p.color, fontSize: 14, fontWeight: 700 }}>
          {p.name}: {p.value}
        </p>
      ))}
    </div>
  );
};

export default function Overview() {
  const { data: overview, isLoading: lo } = useQuery({
    queryKey: ['overview'],
    queryFn: () => getOverview().then((r) => r.data.data),
  });
  const { data: districts, isLoading: ld } = useQuery({
    queryKey: ['districts'],
    queryFn: () => getReportsByDistrict().then((r) => r.data.data),
  });
  const { data: damage, isLoading: ldm } = useQuery({
    queryKey: ['damage'],
    queryFn: () => getDamageDistribution().then((r) => r.data.data),
  });
  const { data: priority, isLoading: lp } = useQuery({
    queryKey: ['priority'],
    queryFn: () => getPriorityRanking(5).then((r) => r.data.data),
  });

  if (lo) return <Spin size="large" style={{ display: 'block', margin: '100px auto' }} />;

  const stats = [
    { icon: <MdAssignment />, label: 'Total Laporan', value: overview?.total_reports || 0, color: '#6366F1', bg: 'rgba(99, 102, 241, 0.1)', gradient: 'linear-gradient(135deg, rgba(99, 102, 241, 0.08), rgba(129, 140, 248, 0.04))' },
    { icon: <MdBuild />, label: 'Dalam Proses', value: overview?.in_progress || 0, color: '#F59E0B', bg: 'rgba(245, 158, 11, 0.1)', gradient: 'linear-gradient(135deg, rgba(245, 158, 11, 0.08), rgba(251, 191, 36, 0.04))' },
    { icon: <MdCheckCircle />, label: 'Selesai Bulan Ini', value: overview?.completed_this_month || 0, color: '#10B981', bg: 'rgba(16, 185, 129, 0.1)', gradient: 'linear-gradient(135deg, rgba(16, 185, 129, 0.08), rgba(52, 211, 153, 0.04))' },
  ];

  return (
    <div>
      <div className="page-header">
        <div>
          <h1 style={{ marginBottom: 6 }}>Dashboard Overview</h1>
          <p style={{ fontSize: 15, color: '#94A3B8', fontWeight: 500 }}>
            Selamat datang kembali! Berikut ringkasan kondisi infrastruktur jalan.
          </p>
        </div>
      </div>

      <div className="stat-cards">
        {stats.map((s, i) => (
          <motion.div 
            className="stat-card" 
            key={i}
            style={{ background: s.gradient }}
            whileHover={{ y: -8, scale: 1.02 }}
            transition={{ type: 'spring', stiffness: 300, damping: 20 }}
          >
            <div className="stat-icon" style={{ background: s.bg, color: s.color }}>
              {s.icon}
            </div>
            <motion.div 
              className="stat-value"
              initial={{ opacity: 0, scale: 0.5 }}
              animate={{ opacity: 1, scale: 1 }}
              transition={{ delay: 0.2 + i * 0.1, type: 'spring', stiffness: 200 }}
            >
              {s.value}
            </motion.div>
            <div className="stat-label">{s.label}</div>
          </motion.div>
        ))}
      </div>

      <div className="chart-grid">
        <div className="chart-card">
          <h3>Laporan per Kecamatan</h3>
          {ld ? <Spin /> : (
            <ResponsiveContainer width="100%" height={300}>
              <BarChart data={districts || []} barCategoryGap="20%">
                <defs>
                  <linearGradient id="barGrad" x1="0" y1="0" x2="0" y2="1">
                    <stop offset="0%" stopColor="#6366F1" stopOpacity={1} />
                    <stop offset="100%" stopColor="#6366F1" stopOpacity={0.6} />
                  </linearGradient>
                </defs>
                <CartesianGrid strokeDasharray="3 3" stroke="#F1F5F9" vertical={false} />
                <XAxis dataKey="district" fontSize={12} stroke="#94A3B8" axisLine={false} tickLine={false} />
                <YAxis fontSize={12} stroke="#94A3B8" axisLine={false} tickLine={false} />
                <Tooltip content={<CustomTooltip />} cursor={{ fill: 'rgba(99, 102, 241, 0.04)', radius: 8 }} />
                <Bar dataKey="total" fill="url(#barGrad)" radius={[8, 8, 0, 0]} name="Laporan" />
              </BarChart>
            </ResponsiveContainer>
          )}
        </div>

        <div className="chart-card">
          <h3>Tingkat Kerusakan</h3>
          {ldm ? <Spin /> : (
            <ResponsiveContainer width="100%" height={300}>
              <PieChart>
                <Pie
                  data={(damage || []).map((d) => ({ name: d.damage_level, value: d.total }))}
                  cx="50%"
                  cy="50%"
                  outerRadius={95}
                  innerRadius={65}
                  dataKey="value"
                  stroke="none"
                  label={({ name, percent }) => `${name} (${(percent * 100).toFixed(0)}%)`}
                  labelLine={{ stroke: '#CBD5E1', strokeWidth: 1 }}
                >
                  {(damage || []).map((_, i) => (
                    <Cell key={i} fill={COLORS[i % COLORS.length]} />
                  ))}
                </Pie>
                <Legend 
                  iconType="circle" 
                  iconSize={8}
                  wrapperStyle={{ fontSize: 13, fontWeight: 600 }}
                />
                <Tooltip content={<CustomTooltip />} />
              </PieChart>
            </ResponsiveContainer>
          )}
        </div>
      </div>

      <motion.div 
        className="priority-table"
        initial={{ opacity: 0, y: 20 }}
        animate={{ opacity: 1, y: 0 }}
        transition={{ delay: 0.4, duration: 0.5 }}
      >
        <h3 style={{ marginBottom: 20, fontSize: 18, fontWeight: 800, letterSpacing: '-0.3px', color: '#0F172A', display: 'flex', alignItems: 'center', gap: 10 }}>
          <span style={{ width: 4, height: 20, borderRadius: 2, background: 'linear-gradient(180deg, #6366F1, #A5B4FC)', display: 'inline-block' }} />
          Ranking Prioritas
        </h3>
        {lp ? <Spin /> : (
          <table>
            <thead>
              <tr>
                <th style={th}>#</th>
                <th style={th}>Lokasi</th>
                <th style={th}>Skor</th>
                <th style={th}>Kerusakan</th>
                <th style={th}>Status</th>
              </tr>
            </thead>
            <tbody>
              {(priority || []).map((r, i) => (
                <motion.tr 
                  key={r.id}
                  initial={{ opacity: 0, x: -10 }}
                  animate={{ opacity: 1, x: 0 }}
                  transition={{ delay: 0.5 + i * 0.05 }}
                >
                  <td style={td}>
                    <span style={{ 
                      width: 28, height: 28, borderRadius: 8, display: 'inline-flex', alignItems: 'center', justifyContent: 'center',
                      background: i === 0 ? 'linear-gradient(135deg, #F59E0B, #FBBF24)' : i === 1 ? 'linear-gradient(135deg, #94A3B8, #CBD5E1)' : i === 2 ? 'linear-gradient(135deg, #CD7F32, #DDA15E)' : '#F1F5F9',
                      color: i < 3 ? '#FFF' : '#64748B',
                      fontSize: 12, fontWeight: 800, 
                    }}>{i + 1}</span>
                  </td>
                  <td style={{ ...td, fontWeight: 600 }}>{r.address || 'N/A'}</td>
                  <td style={td}>
                    <div style={{ display: 'flex', alignItems: 'center', gap: 8 }}>
                      <div style={{ 
                        width: 48, height: 6, borderRadius: 3, background: '#F1F5F9', overflow: 'hidden',
                      }}>
                        <motion.div 
                          style={{ 
                            height: '100%', borderRadius: 3,
                            background: r.priority_score >= 67 ? 'linear-gradient(90deg, #EF4444, #F87171)' : r.priority_score >= 34 ? 'linear-gradient(90deg, #F59E0B, #FBBF24)' : 'linear-gradient(90deg, #10B981, #34D399)',
                          }}
                          initial={{ width: 0 }}
                          animate={{ width: `${r.priority_score}%` }}
                          transition={{ delay: 0.6 + i * 0.05, duration: 0.8, ease: 'easeOut' }}
                        />
                      </div>
                      <span style={{
                        fontWeight: 800, fontSize: 14,
                        color: r.priority_score >= 67 ? '#EF4444' : r.priority_score >= 34 ? '#F59E0B' : '#10B981',
                      }}>
                        {r.priority_score}
                      </span>
                    </div>
                  </td>
                  <td style={td}>
                    <span className={`damage-badge ${r.damage_level}`}>{r.damage_level}</span>
                  </td>
                  <td style={td}>
                    <span className={`status-badge ${r.status}`}>{STATUS_LABELS[r.status] || r.status}</span>
                  </td>
                </motion.tr>
              ))}
            </tbody>
          </table>
        )}
      </motion.div>
    </div>
  );
}

const th = { textAlign: 'left', padding: '16px 20px', fontSize: 12, color: '#64748B', fontWeight: 700, textTransform: 'uppercase', letterSpacing: '0.8px' };
const td = { padding: '18px 20px', fontSize: 15, color: '#0F172A' };
