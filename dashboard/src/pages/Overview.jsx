import { useQuery } from '@tanstack/react-query';
import { Spin } from 'antd';
import { MdAssignment, MdBuild, MdCheckCircle, MdTimer } from 'react-icons/md';
import {
  BarChart, Bar, XAxis, YAxis, CartesianGrid, Tooltip, ResponsiveContainer,
  PieChart, Pie, Cell, Legend,
} from 'recharts';
import { getOverview, getReportsByDistrict, getDamageDistribution, getPriorityRanking } from '../api';

const COLORS = ['#10B981', '#F59E0B', '#E11D48'];
const STATUS_LABELS = {
  submitted: 'Submitted',
  verified: 'Verified',
  scheduled: 'Scheduled',
  under_repair: 'Under Repair',
  completed: 'Completed',
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
    { icon: <MdAssignment />, label: 'Total Laporan', value: overview?.total_reports || 0, color: '#4F46E5', bg: 'rgba(79, 70, 229, 0.1)' },
    { icon: <MdBuild />, label: 'Dalam Proses', value: overview?.in_progress || 0, color: '#F59E0B', bg: '#FEF3C7' },
    { icon: <MdCheckCircle />, label: 'Selesai Bulan Ini', value: overview?.completed_this_month || 0, color: '#10B981', bg: '#D1FAE5' },
    { icon: <MdTimer />, label: 'Rata-rata Respon', value: `${overview?.avg_response_days || 0} Hari`, color: '#E11D48', bg: '#FFE4E6' },
  ];

  return (
    <div>
      <div className="page-header">
        <h1>Dashboard Overview</h1>
      </div>

      <div className="stat-cards">
        {stats.map((s, i) => (
          <div className="stat-card" key={i}>
            <div className="stat-icon" style={{ background: s.bg, color: s.color }}>
              {s.icon}
            </div>
            <div className="stat-value">{s.value}</div>
            <div className="stat-label">{s.label}</div>
          </div>
        ))}
      </div>

      <div className="chart-grid">
        <div className="chart-card">
          <h3>Laporan per Kecamatan</h3>
          {ld ? <Spin /> : (
            <ResponsiveContainer width="100%" height={280}>
              <BarChart data={districts || []}>
                <CartesianGrid strokeDasharray="3 3" stroke="#F1F5F9" />
                <XAxis dataKey="district" fontSize={12} stroke="#64748B" />
                <YAxis fontSize={12} stroke="#64748B" />
                <Tooltip cursor={{fill: '#F8FAFC'}} contentStyle={{ borderRadius: 12, border: 'none', boxShadow: '0 4px 20px rgba(0,0,0,0.08)' }} />
                <Bar dataKey="total" fill="#4F46E5" radius={[6, 6, 0, 0]} />
              </BarChart>
            </ResponsiveContainer>
          )}
        </div>

        <div className="chart-card">
          <h3>Tingkat Kerusakan</h3>
          {ldm ? <Spin /> : (
            <ResponsiveContainer width="100%" height={280}>
              <PieChart>
                <Pie
                  data={(damage || []).map((d) => ({ name: d.damage_level, value: d.total }))}
                  cx="50%"
                  cy="50%"
                  outerRadius={90}
                  innerRadius={60}
                  dataKey="value"
                  label={({ name, percent }) => `${name} (${(percent * 100).toFixed(0)}%)`}
                >
                  {(damage || []).map((_, i) => (
                    <Cell key={i} fill={COLORS[i % COLORS.length]} />
                  ))}
                </Pie>
                <Legend />
                <Tooltip contentStyle={{ borderRadius: 12, border: 'none', boxShadow: '0 4px 20px rgba(0,0,0,0.08)' }} />
              </PieChart>
            </ResponsiveContainer>
          )}
        </div>
      </div>

      <div className="priority-table">
        <h3 style={{ marginBottom: 16, fontSize: 18, fontWeight: 700, letterSpacing: '-0.3px', color: '#0F172A' }}>Ranking Prioritas</h3>
        {lp ? <Spin /> : (
          <table style={{ width: '100%', borderCollapse: 'collapse' }}>
            <thead>
              <tr style={{ borderBottom: '2px solid #F1F5F9' }}>
                <th style={th}>#</th>
                <th style={th}>Lokasi</th>
                <th style={th}>Skor</th>
                <th style={th}>Kerusakan</th>
                <th style={th}>Status</th>
              </tr>
            </thead>
            <tbody>
              {(priority || []).map((r, i) => (
                <tr key={r.id} style={{ borderBottom: '1px solid #F8FAFC' }}>
                  <td style={td}>{i + 1}</td>
                  <td style={{ ...td, fontWeight: 500 }}>{r.address || 'N/A'}</td>
                  <td style={td}>
                    <span style={{
                      fontWeight: 800,
                      color: r.priority_score >= 67 ? '#E11D48' : r.priority_score >= 34 ? '#F59E0B' : '#10B981',
                    }}>
                      {r.priority_score}
                    </span>
                  </td>
                  <td style={td}>
                    <span className={`damage-badge ${r.damage_level}`}>{r.damage_level}</span>
                  </td>
                  <td style={td}>
                    <span className={`status-badge ${r.status}`}>{STATUS_LABELS[r.status] || r.status}</span>
                  </td>
                </tr>
              ))}
            </tbody>
          </table>
        )}
      </div>
    </div>
  );
}

const th = { textAlign: 'left', padding: '16px 16px', fontSize: 13, color: '#64748B', fontWeight: 600, textTransform: 'uppercase', letterSpacing: '0.5px' };
const td = { padding: '16px 16px', fontSize: 15, color: '#0F172A' };
