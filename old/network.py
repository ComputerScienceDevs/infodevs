import numpy as np

def sigmoid(t):
    return 1/(1+np.exp(-t))

# Derivative of sigmoid
def sigmoid_derivative(p):
    return p * (1 - p)

class Layer():
    def __init__(self, w):
        self.weights = w

    def getResult(self, input):
        self.lastResult = sigmoid(np.dot(input, self.weights))
        return self.lastResult
        
class NNetwork():
    @staticmethod
    def CreateNetwork(Size : int, InputStruct):
        NLayers = []
        for i in range(Size):
            # Fehlerquelle
            if(len(NLayers) == 0):
                NLayers.append(Layer(np.random.rand(InputStruct.shape[1], 4)))
            else:
                NLayers.append(Layer(np.random.rand(4, 1)))
        return NLayers

    @staticmethod
    def FeedForward(NNetworkLayers, Input):
        Last = Input
        for NLayer in NNetworkLayers:
            NResult = NLayer.getResult(Last)
            Last = NResult
        return Last

Input = np.array(
    ([0, 0, 1],
     [0, 1, 1],
     [1, 0, 1],
     [1, 1, 1]),
    dtype=float
)

Outputs = np.array(([0], [1], [1], [0]), dtype=float)

NeuralNetwork = NNetwork.CreateNetwork(2, Input)
print(NNetwork.FeedForward(NeuralNetwork, Input))