import axios from 'axios';
import { ref } from 'vue';

export interface DashboardStats {
  total_income: number;
  total_expense: number;
  net_balance: number;
  savings_rate: number;
}

export interface TodayStats {
  income: number;
  expense: number;
  net: number;
}

export interface ChartMonth {
  label: string;
  income: number;
  expense: number;
}

export interface TopSpendingCategory {
  name: string;
  icon: string;
  color: string;
  amount: number;
  pct: number;
}

export interface RecentTransaction {
  id: number;
  title: string;
  category: string;
  categoryColor: string;
  icon: string;
  amount: number;
  type: 'income' | 'expense';
  date: string;
}

// Global State
const stats = ref<DashboardStats>({
  total_income: 0,
  total_expense: 0,
  net_balance: 0,
  savings_rate: 0,
});

const todayStats = ref<TodayStats>({
  income: 0,
  expense: 0,
  net: 0,
});

const cashFlow = ref<ChartMonth[]>([]);
const topSpending = ref<TopSpendingCategory[]>([]);
const recentTransactions = ref<RecentTransaction[]>([]);
const isLoading = ref(false);

export function useDashboardState() {
  
  async function fetchStats(): Promise<void> {
    try {
      isLoading.value = true;
      const response = await axios.get<DashboardStats>('/dashboard/stats');
      stats.value = response.data;
    } catch (error) {
      console.error('Failed to fetch dashboard stats:', error);
    } finally {
      isLoading.value = false;
    }
  }

  async function fetchTodayStats(): Promise<void> {
    try {
      isLoading.value = true;
      const response = await axios.get<TodayStats>('/dashboard/today');
      todayStats.value = response.data;
    } catch (error) {
      console.error('Failed to fetch today stats:', error);
    } finally {
      isLoading.value = false;
    }
  }

  async function fetchCashFlow(): Promise<void> {
    try {
      isLoading.value = true;
      const response = await axios.get<ChartMonth[]>('/dashboard/cash-flow');
      cashFlow.value = response.data;
    } catch (error) {
      console.error('Failed to fetch cash flow data:', error);
    } finally {
      isLoading.value = false;
    }
  }

  async function fetchTopSpending(): Promise<void> {
    try {
      isLoading.value = true;
      const response = await axios.get<TopSpendingCategory[]>('/dashboard/top-spending');
      topSpending.value = response.data;
    } catch (error) {
      console.error('Failed to fetch top spending data:', error);
    } finally {
      isLoading.value = false;
    }
  }

  async function fetchRecentTransactions(): Promise<void> {
    try {
      isLoading.value = true;
      const response = await axios.get<RecentTransaction[]>('/dashboard/recent-transactions');
      recentTransactions.value = response.data;
    } catch (error) {
      console.error('Failed to fetch recent transactions:', error);
    } finally {
      isLoading.value = false;
    }
  }

  return {
    stats,
    todayStats,
    cashFlow,
    topSpending,
    recentTransactions,
    isLoading,
    fetchStats,
    fetchTodayStats,
    fetchCashFlow,
    fetchTopSpending,
    fetchRecentTransactions,
  };
}
