"""
FreeMoPay Integration Services

Architecture simple basée sur les callbacks natifs FreeMoPay.
Pas de polling, pas de Celery, juste des webhooks.

Usage:
    from api.services.freemopay import PaymentService, CallbackHandler

    # Init payment (< 5s)
    payment_service = PaymentService()
    result = payment_service.init_payment(user, package, phone, amount)

    # Handle callback (automatic)
    callback_handler = CallbackHandler()
    callback_handler.process(callback_data)
"""

from .payment import PaymentService
from .callback import CallbackHandler
from .status import StatusService
from .exceptions import (
    FreemoPayError,
    FreemoPayAuthError,
    FreemoPayPaymentError,
    FreemoPayCallbackError
)

__all__ = [
    'PaymentService',
    'CallbackHandler',
    'StatusService',
    'FreemoPayError',
    'FreemoPayAuthError',
    'FreemoPayPaymentError',
    'FreemoPayCallbackError',
]

__version__ = '2.0.0'
