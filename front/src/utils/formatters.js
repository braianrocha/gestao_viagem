//Tentando formatar a data...
export const formatDate = (dateString) => { 
    if (!dateString) return '---';
    const date = new Date(dateString);
    return new Intl.DateTimeFormat('pt-BR', { timeZone: 'UTC' }).format(date);
}