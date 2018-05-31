import numpy as np
from sklearn import svm
from sklearn import linear_model
from sklearn import tree
import pandas as pd
import os
import pandas as pd
from sklearn import preprocessing
from sklearn import svm
from sklearn.svm import SVC
import pickle


class SVMWrapper:

    ## stock information for training and testing
    # open, close, high, low volume
    train_data_x = None
    # percentage
    train_data_y = None
    # open, close, high, low volume
    test_data_x = None
    # percentage
    test_data_y = None

    ## the SVM model
    # the model that has been trained
    model = None


    def init(self):
        pass


    def train(self):

        # scale data prior to predict
        train_data_x_scaled = preprocessing.StandardScaler().fix(self.train_data_x)

        # create SVC object
        self.model = svm.SVC()

        # fit training data
        self.model.fit(train_data_x_scaled, self.train_data_y)


    def test(self):

        # scale data prior to predict
        test_data_x_scaled = preprocessing.StandardScaler().fix(self.test_data_x)

        # get predicted volumes
        pred_y = self.model.predict(self.test_data_x_scaled)

        # Accuracy = (TP + TN) / (TP + TN + FP + FN)
        # accuracy_score(self.test_data_y, pred_y)

        # Recall = TP / (TP+FN)
        # recall_score(self.test_data_y, pred_y)

        # Precision = TP / (TP+FP)
        f1 = f1_score(self.test_data_y, pred_y)

        # return precision/f1 score
        return f1
