# api/services/notification_service.py
import logging
from ..models import NotificationConfig
from .nexah_service import NexahService
from .whatsapp_service import WhatsAppService

logger = logging.getLogger(__name__)

class NotificationService:
    """Service to handle sending notifications via configured channels"""
    
    @classmethod
    def send_otp(cls, recipient, otp_code, message=None):
        """
        Send OTP code using the configured default channel
        
        Args:
            recipient: Recipient phone number
            otp_code: The OTP code to send
            message: Optional custom message (used for SMS only)
        
        Returns:
            dict: Response with success status and details
        """
        # Get current configuration
        config = NotificationConfig.get_config()
        
        # Default SMS message if none provided
        if not message:
            message = f"Votre code de vérification Ngoma est : '{otp_code}'. Il expire dans 5 minutes."
        
        # Choose the appropriate service based on configuration
        if config.default_channel == 'whatsapp':
            logger.info(f"Sending OTP via WhatsApp to {recipient}")
            # Pour WhatsApp: ignore le message et utilise le template de la config
            return WhatsAppService.send_otp(
                recipient=recipient,
                otp_code=otp_code
            )
        else:  # Default to SMS
            logger.info(f"Sending OTP via SMS to {recipient}")
            # Pour SMS: utilise le message texte
            return NexahService.send_sms(
                recipient=recipient,
                message=message
            )