import numpy as np

def sigmoid(t):
    return 1/(1+np.exp(-t))

# Derivative of sigmoid
def sigmoid_derivative(p):
    return p * (1 - p)

class NeuralNetwork:
    def __init__(self, InputShape):
        # ToDo 
        self.weights = [np.random.rand(InputShape[1], InputShape[0]), np.random.rand(4,1)]
        print(self.weights[0])
        print(self.weights[1])
        print("--------")
    
    def feedforward(self, x):
        lastOutput = x
        self.layerStates = []
        for weight in self.weights:
            lastOutput = sigmoid(np.dot(lastOutput, weight))
            self.layerStates.append(lastOutput)

        return lastOutput
    
    def backprop(self, x, y):
        # ToDo
        lastOutput  = self.feedforward(x)

        d_weights1 = np.dot(self.layerStates[0].T, (2*(y - lastOutput) * sigmoid_derivative(lastOutput)))
        d_weights0 = np.dot(x.T, (np.dot(2*(y - lastOutput) * sigmoid_derivative(lastOutput), self.weights[1].T) * sigmoid_derivative(self.layerStates[0])))

        self.weights[0] += d_weights0
        self.weights[1] += d_weights1

Input = np.array(
    ([0, 1, 1],
    [0, 0, 1],
    [1, 0, 1],
    [0, 0, 1]),
    dtype=float
)

Outputs = np.array(([1], [0], [1], [0]), dtype=float)

#Input2 = np.array(
#    ([1, 1, 0],
#    [0, 1, 1],
#    [1, 0, 0],
#    [0, 0, 1]),
#    dtype=float
#)

#Outputs2 = np.array(([1], [1], [0], [0]), dtype=float)

NNetwork = NeuralNetwork(Input.shape)
for i in range(1500):
    NNetwork.backprop(Input, Outputs)

print(NNetwork.feedforward(Input))