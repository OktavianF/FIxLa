import { useQuery } from '@tanstack/react-query';
import { Table, Tag, Spin, Progress } from 'antd';
import { getPriorityRanking } from '../api';

const DAMAGE_COLORS = { ringan: '#10B981', sedang: '#F59E0B', berat: '#E11D48' };
const STATUS_LABELS = {
  submitted: 'Submitted', verified: 'Verified', scheduled: 'Scheduled',
  under_repair: 'Under Repair', completed: 'Completed',
};
const STATUS_COLORS = {
  submitted: '#64748B', verified: '#4F46E5', scheduled: '#F59E0B',
  under_repair: '#7E22CE', completed: '#10B981',
};

export default function Priority() {
  const { data, isLoading } = useQuery({
    queryKey: ['priority-full'],
    queryFn: () => getPriorityRanking(50).then((r) => r.data.data),
  });

  const columns = [
    { title: 'Rank', key: 'rank', width: 60, render: (_, __, i) => <strong>#{i + 1}</strong> },
    { title: 'Lokasi', dataIndex: 'address', ellipsis: true },
    { title: 'Kecamatan', dataIndex: 'district', width: 120 },
    {
      title: 'Skor Prioritas',
      dataIndex: 'priority_score',
      width: 160,
      sorter: (a, b) => a.priority_score - b.priority_score,
      defaultSortOrder: 'descend',
      render: (v) => (
        <div style={{ display: 'flex', alignItems: 'center', gap: 8 }}>
          <Progress
            percent={v}
            size="small"
            strokeColor={v >= 67 ? '#E63946' : v >= 34 ? '#F4A261' : '#2A9D8F'}
            showInfo={false}
            style={{ width: 80 }}
          />
          <strong style={{ color: v >= 67 ? '#E63946' : v >= 34 ? '#F4A261' : '#2A9D8F' }}>
            {v}
          </strong>
        </div>
      ),
    },
    {
      title: 'Kerusakan',
      dataIndex: 'damage_level',
      width: 100,
      render: (v) => <Tag color={DAMAGE_COLORS[v]}>{v}</Tag>,
    },
    {
      title: 'Laporan',
      dataIndex: 'report_count',
      width: 80,
      render: (v) => <span>{v}×</span>,
    },
    {
      title: 'Status',
      dataIndex: 'status',
      width: 120,
      render: (v) => <Tag color={STATUS_COLORS[v]}>{STATUS_LABELS[v] || v}</Tag>,
    },
  ];

  return (
    <div>
      <div className="page-header">
        <h1>Ranking Prioritas Perbaikan</h1>
      </div>

      <div style={{ background: '#fff', borderRadius: 12, padding: 4, boxShadow: '0 1px 3px rgba(0,0,0,0.08)' }}>
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
      </div>
    </div>
  );
}
