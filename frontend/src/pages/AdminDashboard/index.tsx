import React, { useState, useEffect } from 'react';
import apiClient from '../../services/api';
import { Card, Col, Container, Row } from 'react-bootstrap';

const AdminDashboard: React.FC = () => {
  const [stats, setStats] = useState({
    total_users: 0,
    total_schools: 0,
    total_activities: 0,
    total_contents: 0
  });

  useEffect(() => {
      const fetchStats = async () => {
          try {
              const token = localStorage.getItem('token');
              const response = await apiClient.get('/admin/dashboard-stats', {
                  headers: {
                      Authorization: `Bearer ${token}`,
                  },
              });
              setStats(response.data);
          } catch (error) {
              console.error('Erro ao buscar estatísticas', error);
          }
      };

      fetchStats();
  }, []);

  return (
    <Container fluid className="admin-dashboard p-4">
      <h1 className="mb-4">Painel Administrativo</h1>
      <Row>
        <Col md={3}>
          <Card className="mb-4 shadow-sm">
            <Card.Body>
              <Card.Title>Total de Usuários</Card.Title>
              <Card.Text>{stats.total_users}</Card.Text>
            </Card.Body>
          </Card>
        </Col>

        <Col md={3}>
          <Card className="mb-4 shadow-sm">
            <Card.Body>
              <Card.Title>Total de Escolas</Card.Title>
              <Card.Text>{stats.total_schools}</Card.Text>
            </Card.Body>
          </Card>
        </Col>

        <Col md={3}>
          <Card className="mb-4 shadow-sm">
            <Card.Body>
              <Card.Title>Atividades</Card.Title>
              <Card.Text>{stats.total_activities}</Card.Text>
            </Card.Body>
          </Card>
        </Col>

        <Col md={3}>
          <Card className="mb-4 shadow-sm">
            <Card.Body>
              <Card.Title>Conteúdos</Card.Title>
              <Card.Text>{stats.total_contents}</Card.Text>
            </Card.Body>
          </Card>
        </Col>
      </Row>
    </Container>
  );
};

export default AdminDashboard;