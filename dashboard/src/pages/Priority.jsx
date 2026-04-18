import { useQuery } from '@tanstack/react-query';
import { Table, Tag, Spin, Progress } from 'antd';
import { motion } from 'framer-motion';
import { getPriorityRanking } from '../api';

const DAMAGE_COLORS = { ringan: '#10B981', sedang: '#F59E0B', berat: '#EF4444' };
const STATUS_LABELS = {
  submitted: 'Submitted', verified: 'Verified', scheduled: 'Scheduled',
  under_repair: 'Under Repair', completed: 'Completed',
};
const STATUS_COLORS = {
  submitted: '#64748B', verified: '#6366F1', scheduled: '#F59E0B',
  under_repair: '#7C3AED', completed: '#10B981',
};

export default function Priority() {
  const { data, isLoading } = useQuery({
    queryKey: ['priority-full'],
    queryFn: () => getPriorityRanking(50).then((r) => r.data.data),
  });

  const columns = [
    { 
      title: 'Rank', key: 'rank', width: 70, 
      render: (_, __, i) => (
        <span style={{
          width: 32, height: 32, borderRadius: 10, display: 'inline-flex', alignItems: 'center', justifyContent: 'center',
          background: i === 0 ? 'linear-gradient(135deg, #F59E0B, #FBBF24)' : i === 1 ? 'linear-gradient(135deg, #94A3B8, #CBD5E1)' : i === 2 ? 'linear-gradient(135deg, #CD7F32, #DDA15E)' : '#F1F5F9',
          color: i < 3 ? '#FFF' : '#64748B',
          fontSize: 13, fontWeight: 800,
        }}>#{i + 1}</span>
      ),
    },
    { title: 'Lokasi', dataIndex: 'address', ellipsis: true, render: (v) => <span style={{ fontWeight: 600 }}>{v}</span> },
    { title: 'Kecamatan', dataIndex: 'district', width: 130 },
    {
      title: 'Skor Prioritas',
      dataIndex: 'priority_score',
      width: 180,
      sorter: (a, b) => a.priority_score - b.priority_score,
      defaultSortOrder: 'descend',
      render: (v) => (
        <div style={{ display: 'flex', alignItems: 'center', gap: 10 }}>
          <div style={{ width: 80, height: 8, borderRadius: 4, background: '#F1F5F9', overflow: 'hidden' }}>
            <div style={{
              height: '100%', borderRadius: 4, width: `${v}%`,
              background: v >= 67 ? 'linear-gradient(90deg, #EF4444, #F87171)' : v >= 34 ? 'linear-gradient(90deg, #F59E0B, #FBBF24)' : 'linear-gradient(90deg, #10B981, #34D399)',
              transition: 'width 0.8s ease',
            }} />
          </div>
          <strong style={{ 
            color: v >= 67 ? '#EF4444' : v >= 34 ? '#F59E0B' : '#10B981',
            fontSize: 15, fontVariantNumeric: 'tabular-nums',
          }}>
            {v}
          </strong>
        </div>
      ),
    },
    {
      title: 'Kerusakan',
      dataIndex: 'damage_level',
      width: 110,
      render: (v) => (
        <span style={{
          padding: '5px 12px', borderRadius: 10, fontSize: 12, fontWeight: 700,
          background: v === 'berat' ? '#FEF2F2' : v === 'sedang' ? '#FFF7ED' : '#ECFDF5',
          color: DAMAGE_COLORS[v],
        }}>{v}</span>
      ),
    },
    {
      title: 'Laporan',
      dataIndex: 'report_count',
      width: 90,
      render: (v) => (
        <span style={{ 
          background: '#F1F5F9', padding: '4px 10px', borderRadius: 8, 
          fontSize: 13, fontWeight: 700, color: '#475569',
        }}>{v}×</span>
      ),
    },
    {
      title: 'Status',
      dataIndex: 'status',
      width: 130,
      render: (v) => (
        <span style={{
          padding: '5px 12px', borderRadius: 10, fontSize: 12, fontWeight: 700,
          background: v === 'completed' ? '#ECFDF5' : v === 'verified' ? '#EEF2FF' : v === 'under_repair' ? '#FAF5FF' : v === 'scheduled' ? '#FFF7ED' : '#F1F5F9',
          color: STATUS_COLORS[v],
        }}>{STATUS_LABELS[v] || v}</span>
      ),
    },
  ];

  return (
    <div>
      <div className="page-header">
        <div>
          <h1 style={{ marginBottom: 6 }}>Ranking Prioritas Perbaikan</h1>
          <p style={{ fontSize: 15, color: '#94A3B8', fontWeight: 500 }}>Urutkan lokasi berdasarkan skor prioritas tertinggi.</p>
        </div>
      </div>

      <motion.div 
        initial={{ opacity: 0, y: 16 }}
        animate={{ opacity: 1, y: 0 }}
        transition={{ duration: 0.4 }}
        style={{ background: '#fff', borderRadius: 20, padding: 4, boxShadow: '0 4px 6px -1px rgba(0,0,0,0.04)' }}
      >
        {isLoading ? (
          <Spin size="large" style={{ display: 'block', margin: '60px auto' }} />
        ) : (
          <Table
            dataSource={data || []}
            columns={columns}
            rowKey="id"
            pagination={{ pageSize: 15 }}
            size="middle"
          />
        )}
      </motion.div>
    </div>
  );
}
