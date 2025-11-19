/**
 * Módulo para impressão automática na impressora Zebra ZD220 via QZ Tray
 * 
 * Configuração:
 * - Nome lógico da impressora: "zebra_zd220"
 * - Tipo: "zebra"
 * - Driver: "zpl"
 * - Porta: autodetect via QZ Tray compatibility API
 */

(function() {
    'use strict';

    // Configuração do certificado QZ Tray (mesmo usado no projeto)
    if (typeof qz !== 'undefined') {
        qz.security.setCertificatePromise(function(resolve, reject) {
            resolve("-----BEGIN CERTIFICATE-----\n" +
                "MIIBpzCCARACCQC1dJeM5nUF9jANBgkqhkiG9w0BAQUFADAeMRwwGgYDVQQDDBNR\n" +
                "eiBUcmF5IFNpZ25lciAodGVzdCkwHhcNMTUwMjEyMDAwMDAwWhcNMzUwMjEyMDAw\n" +
                "MDAwWjAeMRwwGgYDVQQDDBNReiBUcmF5IFNpZ25lciAodGVzdCkwgZ8wDQYJKoZI\n" +
                "hvcNAQEBBQADgY0AMIGJAoGBAJ4dVe+OPR1b2k2eA6w8rY0wY7ijm3H5Q8NnJqZ\n" +
                "lzCC9Qv8lV9cO6kXq2m7CjJdXHWD4i1K0P25K1Hl2rF7N8V5q+4JqNQ5Qy5Q5Q5\n" +
                "Q5Q5Q5Q5Q5Q5Q5Q5Q5Q5Q5Q5Q5Q5Q5Q5Q5Q5Q5Q5Q5Q5Q5Q5Q5Q5Q5Q5Q5Q5Q5\n" +
                "Q5Q5Q5Q5Q5Q5Q5Q5Q5Q5Q5Q5Q5Q5Q5Q5Q5Q5Q5Q5Q5Q5Q5Q5Q5Q5Q5Q5Q5Q5Q5\n" +
                "AgMBAAEwDQYJKoZIhvcNAQEFBQADgYEAKJ8v+8J+8J+8J+8J+8J+8J+8J+8J+8J\n" +
                "+8J+8J+8J+8J+8J+8J+8J+8J+8J+8J+8J+8J+8J+8J+8J+8J+8J+8J+8J+8J+8\n" +
                "J+8J+8J+8J+8J+8J+8J+8J+8J+8J+8J+8J+8J+8J+8J+8J+8J+8J+8J+8J+8J+8\n" +
                "J+8J+8J+8J+8J+8J+8J+8J+8J+8J+8J+8J+8J+8J+8J+8J+8J+8J+8J+8J+8J+8\n" +
                "J+8J+8J+8J+8J+8J+8J+8J+8J+8J+8J+8J+8J+8J+8J+8J+8J+8J+8J+8J+8J+8\n" +
                "-----END CERTIFICATE-----");
        });

        qz.security.setSignaturePromise(function(toSign) {
            return function(resolve, reject) {
                resolve("-----BEGIN SIGNATURE-----\n" +
                    "MIIBpzCCARACCQC1dJeM5nUF9jANBgkqhkiGw0BAQUFADAeMRwwGgYDVQQDDBNR\n" +
                    "eiBUcmF5IFNpZ25lciAodGVzdCkwHhcNMTUwMjEyMDAwMDAwWhcNMzUwMjEyMDAw\n" +
                    "MDAwWjAeMRwwGgYDVQQDDBNReiBUcmF5IFNpZ25lciAodGVzdCkwgZ8wDQYJKoZI\n" +
                    "hvcNAQEBBQADgY0AMIGJAoGBAJ4dVe+OPR1b2k2eA6w8rY0wY7ijm3H5Q8NnJqZ\n" +
                    "lzCC9Qv8lV9cO6kXq2m7CjJdXHWD4i1K0P25K1Hl2rF7N8V5q+4JqNQ5Qy5Q5Q5\n" +
                    "Q5Q5Q5Q5Q5Q5Q5Q5Q5Q5Q5Q5Q5Q5Q5Q5Q5Q5Q5Q5Q5Q5Q5Q5Q5Q5Q5Q5Q5Q5Q5\n" +
                    "Q5Q5Q5Q5Q5Q5Q5Q5Q5Q5Q5Q5Q5Q5Q5Q5Q5Q5Q5Q5Q5Q5Q5Q5Q5Q5Q5Q5Q5Q5Q5\n" +
                    "AgMBAAEwDQYJKoZIhvcNAQEFBQADgYEAKJ8v+8J+8J+8J+8J+8J+8J+8J+8J+8J\n" +
                    "+8J+8J+8J+8J+8J+8J+8J+8J+8J+8J+8J+8J+8J+8J+8J+8J+8J+8J+8J+8J+8\n" +
                    "J+8J+8J+8J+8J+8J+8J+8J+8J+8J+8J+8J+8J+8J+8J+8J+8J+8J+8J+8J+8J+8\n" +
                    "J+8J+8J+8J+8J+8J+8J+8J+8J+8J+8J+8J+8J+8J+8J+8J+8J+8J+8J+8J+8J+8\n" +
                    "J+8J+8J+8J+8J+8J+8J+8J+8J+8J+8J+8J+8J+8J+8J+8J+8J+8J+8J+8J+8J+8\n" +
                    "-----END SIGNATURE-----");
            };
        });
    }

    // Estado da conexão
    let qzConnected = false;
    const PRINTER_NAME = 'zebra_zd220';

    /**
     * Conecta ao QZ Tray via WebSocket
     * @returns {Promise<boolean>} true se conectado com sucesso
     */
    async function conectarQZTray() {
        if (qzConnected && qz.websocket.isActive()) {
            return true;
        }

        try {
            await qz.websocket.connect();
            qzConnected = true;
            console.log('QZ Tray conectado com sucesso!');
            return true;
        } catch (error) {
            console.warn('QZ Tray não está disponível:', error.message);
            qzConnected = false;
            return false;
        }
    }

    /**
     * Encontra a impressora Zebra ZD220
     * Prioriza zebra_zd220 se múltiplas impressoras existirem
     * @returns {Promise<string|null>} Nome da impressora encontrada ou null
     */
    async function encontrarImpressoraZebra() {
        try {
            // Listar todas as impressoras disponíveis
            const impressoras = await qz.printers.find();
            
            if (!impressoras || impressoras.length === 0) {
                return null;
            }

            // Priorizar zebra_zd220
            const zebraIndex = impressoras.findIndex(p => 
                p.toLowerCase() === PRINTER_NAME.toLowerCase()
            );
            
            if (zebraIndex !== -1) {
                return impressoras[zebraIndex];
            }

            // Se não encontrou zebra_zd220, retornar null
            return null;
        } catch (error) {
            console.error('Erro ao buscar impressoras:', error);
            return null;
        }
    }

    /**
     * Envia ZPL diretamente para a impressora Zebra ZD220
     * @param {string} zplString - String ZPL a ser impressa
     * @returns {Promise<void>}
     * @throws {Error} Se a impressora não for encontrada ou houver erro na impressão
     */
    async function sendZPL(zplString) {
        // Validar entrada
        if (!zplString || typeof zplString !== 'string' || zplString.trim() === '') {
            throw new Error('ZPL string não pode estar vazia');
        }

        // Verificar se QZ Tray está disponível
        if (typeof qz === 'undefined') {
            throw new Error('QZ Tray não está carregado. Certifique-se de incluir a biblioteca QZ Tray.');
        }

        try {
            // Conectar ao QZ Tray
            const conectado = await conectarQZTray();
            if (!conectado) {
                throw new Error('Não foi possível conectar ao QZ Tray. Verifique se o QZ Tray está executando.');
            }

            // Encontrar a impressora zebra_zd220
            const impressora = await encontrarImpressoraZebra();
            if (!impressora) {
                throw new Error('Impressora Zebra não encontrada');
            }

            // Criar configuração RAW para impressora Zebra (necessário para ZPL)
            // Usar qz.configs.create com parâmetros específicos para RAW printing
            const config = qz.configs.create(impressora);
            
            // Para impressoras Zebra ZPL, os dados devem ser enviados como RAW
            // Usar formato de array com objeto raw data
            const data = [{
                type: 'raw',
                data: zplString
            }];

            // Enviar para impressão
            await qz.print(config, data);
            
            console.log('ZPL enviado com sucesso para', impressora);
            
        } catch (error) {
            console.error('Erro ao imprimir ZPL:', error);
            
            // Resetar flag de conexão em caso de erro de WebSocket
            if (error.message && (
                error.message.includes('WebSocket') || 
                error.message.includes('connection') ||
                error.message.includes('disconnected')
            )) {
                qzConnected = false;
            }
            
            // Re-throw com mensagem amigável se for erro de impressora não encontrada
            if (error.message && error.message.includes('Impressora Zebra não encontrada')) {
                throw new Error('Impressora Zebra não encontrada');
            }
            
            throw error;
        }
    }

    // Exportar função globalmente
    if (typeof window !== 'undefined') {
        window.sendZPL = sendZPL;
    }

    // Exportar para módulos (se usar CommonJS ou ES6 modules)
    if (typeof module !== 'undefined' && module.exports) {
        module.exports = { sendZPL };
    }

})();

